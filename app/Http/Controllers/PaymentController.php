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
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->custom_field2; // We mapped Original Order ID here
        $paymentType = $notification->custom_field1;
        $grossAmount = $notification->gross_amount;

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
                    $order->status = 'printing'; // Automate move to production!
                } elseif ($paymentType === 'full') {
                    $order->payment_status = 'paid';
                    $order->deposit_amount = $grossAmount;
                    $order->status = 'printing';
                } elseif ($paymentType === 'rest') {
                    $order->payment_status = 'paid';
                    $order->deposit_amount += $grossAmount; // Pelunasan
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
}
