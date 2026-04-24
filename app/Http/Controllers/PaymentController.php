<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Set up Midtrans Config
     */
    protected function initMidtrans()
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    /**
     * Generate / Retrieve Snap Token for the Order
     */
    public function createPayment(Request $request, Order $order)
    {
        $this->initMidtrans();

        $paymentType = $request->input('type'); // 'dp', 'full', 'rest'

        $grossAmount = 0;
        if ($paymentType === 'dp') {
            $grossAmount = $order->total_amount / 2;
        } elseif ($paymentType === 'full') {
            $grossAmount = $order->total_amount;
        } elseif ($paymentType === 'rest') {
            $grossAmount = $order->total_amount - $order->deposit_amount;
        }

        // Create a unique order_id for Midtrans (Format: {OrderNumber}-{Timestamp})
        $midtransOrderId = $order->order_number . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
            // Custom data map to identify payment context inside webhook
            'custom_field1' => $paymentType, // e.g. dp, full, rest
            'custom_field2' => $order->id,
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Save the midtrans_order_id to the order so we can sync it later
            $order->update([
                'midtrans_order_id' => $midtransOrderId,
                'payment_token' => $snapToken // Optional: reuse this column for the token
            ]);

            // Return to the invoice view with flash session snapToken
            return redirect()->route('customer.orders.show', $order->order_number)
                ->with('snapToken', $snapToken);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memanggil layanan pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Handle Midtrans Webhook (Server-to-Server Callback)
     */
    public function callback(Request $request)
    {
        $this->initMidtrans();

        try {
            $notification = new \Midtrans\Notification();
            Log::info('Midtrans Callback Received', [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'custom_field2' => $notification->custom_field2,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->custom_field2; // We mapped Original Order ID here
        $paymentType = $notification->custom_field1;
        $grossAmount = $notification->gross_amount;

        Log::info('Processing Order Update', [
            'order_id_from_custom' => $orderId,
            'payment_type' => $paymentType
        ]);

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        DB::beginTransaction();

        try {
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($paymentType === 'dp') {
                    $order->payment_status = 'partial';
                    $order->deposit_amount = $grossAmount;
                    if ($order->status === 'unpaid' || $order->status === 'pending') {
                        $order->status = 'paid';
                    }
                } elseif ($paymentType === 'full' || $paymentType === 'rest') {
                    $order->payment_status = 'paid';
                    $order->deposit_amount = $order->total_amount;
                    if ($order->status === 'unpaid' || $order->status === 'pending') {
                        $order->status = 'paid';
                    }
                }
            } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                // If expire on a pending order, we might just let it be, 
                // but if they retry they get a new token anyway.
            } else if ($transactionStatus == 'pending') {
                // Keep pending, waiting for user to pay
            }

            $order->save();
            DB::commit();

            return response()->json(['message' => 'Callback processed']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Callback DB Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * Manual Sync Status (for missing webhooks or local development)
     */
    public function syncStatus(Order $order)
    {
        $this->initMidtrans();

        $midtransOrderId = $order->midtrans_order_id;

        if (!$midtransOrderId) {
            return back()->with('error', 'ID Transaksi Midtrans tidak ditemukan untuk pesanan ini.');
        }

        try {
            $status = \Midtrans\Transaction::status($midtransOrderId);
            Log::info('Midtrans Sync Status Response', (array) $status);
            
            $transactionStatus = $status->transaction_status;
            $paymentType = isset($status->custom_field1) ? $status->custom_field1 : null;
            $grossAmount = $status->gross_amount;

            Log::info("Manual Sync Debug: Order ID: {$order->id}, Midtrans ID: {$midtransOrderId}, Status: {$transactionStatus}, Type: {$paymentType}");

            DB::beginTransaction();
            
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($paymentType === 'dp') {
                    $order->payment_status = 'partial';
                    $order->deposit_amount = $grossAmount;
                    // Initial status after payment should be 'paid' (Antrean Masuk)
                    if ($order->status === 'unpaid' || $order->status === 'pending') {
                        $order->status = 'paid';
                    }
                } elseif ($paymentType === 'full' || $paymentType === 'rest') {
                    $order->payment_status = 'paid';
                    $order->deposit_amount = $order->total_amount;
                    
                    // Set to 'paid' if it was unpaid or pending
                    if ($order->status === 'unpaid' || $order->status === 'pending') {
                        $order->status = 'paid';
                    }
                }
                
                $order->save();
                DB::commit();
                Log::info("Order {$order->order_number} successfully synced to PAID/PARTIAL");
                return back()->with('success', 'Status pembayaran berhasil diperbarui: ' . strtoupper($transactionStatus));
            } else {
                DB::rollBack();
                Log::info("Order {$order->order_number} sync skipped. Midtrans Status: {$transactionStatus}");
                return back()->with('info', 'Status pembayaran di Midtrans saat ini: ' . strtoupper($transactionStatus) . '. Silakan selesaikan pembayaran di simulator.');
            }

        } catch (\Exception $e) {
            Log::error('Manual Sync Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }
}
