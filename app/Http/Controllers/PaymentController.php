<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaywuzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected PaywuzService $paywuzService;

    public function __construct(PaywuzService $paywuzService)
    {
        $this->paywuzService = $paywuzService;
    }

    /**
     * Create / Redirect to Paywuz Payment
     */
    public function createPayment(Request $request, Order $order)
    {
        $paymentType = $request->input('type');

        $grossAmount = $order->total_amount;
        if ($paymentType === 'rest') {
            $grossAmount = $order->total_amount - $order->deposit_amount;
        } else {
            $paymentType = 'full';
        }

        $merchantOrderId = $order->order_number . '-' . $paymentType . '-' . time();

        // Ambil data pembeli dengan fallback aman
        $customerName = substr(trim($order->recipient_name ?: ($order->user->name ?? 'Pelanggan Becks')), 0, 100);
        if (empty($customerName)) {
            $customerName = 'Pelanggan Becks';
        }

        $customerEmail = trim($order->user->email ?? 'customer@becksapparel.com');
        if (empty($customerEmail)) {
            $customerEmail = 'customer@becksapparel.com';
        }

        $rawPhone = $order->recipient_phone ?: ($order->user->phone ?? '081234567890');
        $customerPhone = \App\Helpers\PhoneHelper::normalize($rawPhone) ?: '081234567890';

        $isProduction = config('services.paywuz.is_production', env('PAYWUZ_IS_PRODUCTION', false));

        // Tentukan metode pembayaran:
        // Di sandbox, Paywuz membatasi QRIS maksimal Rp 99.000. Jika lebih, gunakan VA secara otomatis.
        $paymentMethod = $request->input('method', $request->input('payment_method'));
        if (!$paymentMethod) {
            if (!$isProduction && (int) $grossAmount > 99000) {
                $paymentMethod = 'VA';
            } else {
                $paymentMethod = 'QRIS';
            }
        }

        $metadata = [
            'customerName' => $customerName,
            'phone' => $customerPhone,
            'email' => $customerEmail,
            'orderNumber' => $order->order_number,
            'orderId' => $merchantOrderId,
        ];

        $payload = [
            'amount' => (int) $grossAmount,
            'orderId' => $merchantOrderId,
            'customerName' => $customerName,
            'customerEmail' => $customerEmail,
            'customerPhone' => $customerPhone,
            'paymentMethod' => $paymentMethod,
            'redirectUrl' => route('customer.orders.show', $order->order_number),
            'metadata' => $metadata,
        ];

        try {
            try {
                $paymentResponse = $this->paywuzService->createTransaction($payload);
            } catch (\Exception $e) {
                // Jika di sandbox QRIS melebihi limit Rp 99.000, otomatis fallback ke VA (Virtual Account)
                if ($payload['paymentMethod'] === 'QRIS' && str_contains(strtolower($e->getMessage()), 'maximum amount for qris')) {
                    $payload['paymentMethod'] = 'VA';
                    $paymentResponse = $this->paywuzService->createTransaction($payload);
                } else {
                    throw $e;
                }
            }

            // Gunakan transaction ID dari Paywuz atau fallback ke merchantOrderId
            $transactionId = $paymentResponse['data']['id'] ?? $merchantOrderId;
            $checkoutUrl = $paymentResponse['data']['paymentUrl'] ?? null;

            $order->update([
                'payment_gateway_id' => $transactionId,
            ]);

            if ($checkoutUrl) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => true, 'checkout_url' => $checkoutUrl]);
                }
                // Redirect user ke halaman pembayaran Paywuz
                return redirect()->away($checkoutUrl);
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal mendapatkan link pembayaran dari Paywuz.']);
            }
            return back()->with('error', 'Gagal mendapatkan link pembayaran dari Paywuz. ' . json_encode($paymentResponse));
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Handle Paywuz Webhook Callback
     */
    public function callback(Request $request)
    {
        if (!$this->paywuzService->verifyCallbackSignature($request)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        try {
            $data = $request->all();
            Log::info('Paywuz Webhook Received', $data);

            $transactionStatus = strtolower($data['status'] ?? $data['transaction_status'] ?? $data['paymentStatus'] ?? 'pending');
            $transactionId = $data['transactionId'] ?? $data['transaction_id'] ?? $data['id'] ?? null;
            $orderId = $data['orderId'] ?? $data['order_id'] ?? $data['merchantOrderId'] ?? null;
            $referenceId = $data['referenceId'] ?? $data['reference_id'] ?? ''; 
            $grossAmount = (float) ($data['amount'] ?? $data['gross_amount'] ?? 0);

            // Flexible order lookup: by payment_gateway_id, orderId, or parsed order_number
            $order = null;

            if ($transactionId) {
                $order = Order::where('payment_gateway_id', $transactionId)->first();
            }

            if (!$order && $orderId) {
                $order = Order::where('payment_gateway_id', $orderId)->first();
            }

            // Fallback: Parse BCK-... from orderId, referenceId, transactionId, or metadata
            if (!$order) {
                $metadataOrderNumber = $data['metadata']['orderNumber'] ?? $data['metadata']['order_number'] ?? null;
                if ($metadataOrderNumber) {
                    $order = Order::where('order_number', $metadataOrderNumber)->first();
                }
            }

            if (!$order) {
                $combinedId = ($orderId ?: '') . ' ' . ($referenceId ?: '') . ' ' . ($transactionId ?: '');
                if (preg_match('/BCK-[A-Za-z0-9\-]+/i', $combinedId, $matches)) {
                    // Coba cari order_number persis atau prefix sebelum -full/-dp/-rest
                    $matchedNumber = $matches[0];
                    $cleanNumber = preg_replace('/-(full|dp|rest)(-\d+)?$/i', '', $matchedNumber);
                    $order = Order::where('order_number', $matchedNumber)
                        ->orWhere('order_number', $cleanNumber)
                        ->first();
                }
            }

            if (!$order) {
                Log::warning('Paywuz Callback: Order not found for webhook payload', $data);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Detect payment type from reference/orderId (full, dp, rest)
            $combinedIdentifiers = strtolower(($orderId ?: '') . '-' . ($referenceId ?: '') . '-' . ($transactionId ?: ''));
            $paymentType = 'full';
            if (str_contains($combinedIdentifiers, '-dp-')) {
                $paymentType = 'dp';
            } elseif (str_contains($combinedIdentifiers, '-rest-')) {
                $paymentType = 'rest';
            }

            DB::beginTransaction();

            $isPaid = false;
            if (in_array($transactionStatus, ['success', 'settlement', 'paid', 'completed'])) {
                if ($paymentType === 'dp') {
                    $order->payment_status = 'partial';
                    $order->deposit_amount = $grossAmount > 0 ? $grossAmount : ($order->total_amount / 2);
                    if (in_array($order->status, ['unpaid', 'pending'])) {
                        $order->status = 'paid';
                        $isPaid = true;
                    }
                } elseif ($paymentType === 'rest') {
                    $order->payment_status = 'paid';
                    $order->deposit_amount = $order->total_amount;
                } else {
                    $order->payment_status = 'paid';
                    $order->deposit_amount = $order->total_amount;
                    if (in_array($order->status, ['unpaid', 'pending'])) {
                        $order->status = 'paid';
                        $isPaid = true;
                    }
                }

                // Update payment_gateway_id if Paywuz returned a transactionId
                if ($transactionId && $order->payment_gateway_id !== $transactionId) {
                    $order->payment_gateway_id = $transactionId;
                }

                if ($isPaid && $order->user) {
                    try {
                        $order->user->notify(new \App\Notifications\PaymentSuccessNotification($order));
                    } catch (\Exception $ne) {
                        Log::warning('Failed sending payment notification: ' . $ne->getMessage());
                    }
                }
            }

            $order->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Callback processed successfully',
                'order_number' => $order->order_number,
                'status' => $order->status,
                'payment_status' => $order->payment_status
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Paywuz Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Manual Sync Status
     */
    public function syncStatus(Order $order)
    {
        $transactionId = $order->payment_gateway_id;

        if (!$transactionId) {
            return back()->with('error', 'ID Transaksi pembayaran tidak ditemukan untuk pesanan ini.');
        }

        try {
            $statusResponse = $this->paywuzService->getTransactionStatus($transactionId);
            Log::info('Paywuz Sync Response', (array) $statusResponse);
            
            if (!$statusResponse) {
                return back()->with('error', 'Gagal terhubung ke server pembayaran (Timeout). Silakan coba lagi nanti.');
            }
            
            $status = isset($statusResponse['data']['status']) ? $statusResponse['data']['status'] : 'pending';

            if (in_array($status, ['success', 'settlement', 'paid'])) {
                DB::beginTransaction();
                
                // Parse paymentType dari transactionId: ORD-YYYYMMDD-HEXID-dp-TIMESTAMP
                $parts = explode('-', $transactionId);
                $paymentType = count($parts) >= 4 ? $parts[3] : 'full';
                
                if ($paymentType === 'dp') {
                    $order->payment_status = 'partial';
                    $order->deposit_amount = $order->total_amount / 2;
                    if (in_array($order->status, ['unpaid', 'pending'])) {
                        $order->status = 'production';
                        if ($order->user) {
                            $order->user->notify(new \App\Notifications\PaymentSuccessNotification($order));
                        }
                    }
                } elseif ($paymentType === 'rest') {
                    $order->payment_status = 'paid';
                    $order->deposit_amount = $order->total_amount;
                    // Status order biasanya tetap dikirim (shipped) atau selesai
                } else {
                    $order->payment_status = 'paid';
                    $order->deposit_amount = $order->total_amount;
                    if (in_array($order->status, ['unpaid', 'pending'])) {
                        $order->status = 'paid';
                        if ($order->user) {
                            $order->user->notify(new \App\Notifications\PaymentSuccessNotification($order));
                        }
                    }
                }
                
                $order->save();
                DB::commit();
                
                $statusMessage = $order->payment_status === 'partial' ? 'DP' : 'LUNAS';
                return back()->with('success', "Status pembayaran berhasil diperbarui: $statusMessage");
            }

            return back()->with('info', 'Status pembayaran saat ini: ' . strtoupper($status));

        } catch (\Exception $e) {
            Log::error('Manual Sync Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }
}
