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

        $payload = [
            'amount' => (int) $grossAmount,
            'orderId' => $merchantOrderId,
            'customerName' => $order->user->name,
            'customerEmail' => $order->user->email,
            'customerPhone' => $order->user->phone ?? '080000000000',
            'paymentMethod' => 'QRIS', // Default sementara
            'redirectUrl' => route('customer.orders.show', $order->order_number),
        ];

        try {
            $paymentResponse = $this->paywuzService->createTransaction($payload);

            // Gunakan merchantOrderId untuk pengecekan status ke depannya
            $transactionId = $merchantOrderId;
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

            // Fallback: Parse BCK-YYYYMMDD-XXXXX from orderId, referenceId, or transactionId
            if (!$order) {
                $combinedId = ($orderId ?: '') . ' ' . ($referenceId ?: '') . ' ' . ($transactionId ?: '');
                if (preg_match('/BCK-\d{8}-[A-Za-z0-9]+/i', $combinedId, $matches)) {
                    $order = Order::where('order_number', $matches[0])->first();
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
