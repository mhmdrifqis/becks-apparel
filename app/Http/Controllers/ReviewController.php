<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        \Log::info('Review store method hit', ['order_id' => $order->id, 'user_id' => Auth::id(), 'request' => $request->all()]);

        if ($order->user_id !== Auth::id()) {
            \Log::warning('User mismatch');
            abort(403);
        }

        if ($order->status !== 'completed') {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan untuk pesanan yang telah selesai.');
        }

        if ($order->review) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('reviews', 'public');
                    $imagePaths[] = $path;
                }
            }
        }

        // Ambil produk dari order item pertama
        // Dalam skenario ini, kita ambil package pertama dari pesanan.
        // Jika ada banyak item, ulasan akan melekat pada produk pertama atau bisa dibikin looping jika form mendukung per-item.
        $firstItem = $order->orderItems()->first();
        if (!$firstItem) {
             return back()->with('error', 'Pesanan tidak memiliki produk.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'package_id' => $firstItem->package_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'images' => !empty($imagePaths) ? $imagePaths : null,
            'is_visible' => true,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda telah berhasil dikirim.');
    }
}
