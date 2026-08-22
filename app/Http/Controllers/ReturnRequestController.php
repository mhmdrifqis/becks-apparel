<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnRequestController extends Controller
{
    public function store(Request $request, Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan order dalam status shipped atau completed
        if (!in_array($order->status, ['shipped', 'completed'])) {
            return back()->with('error', 'Pesanan belum dapat dikembalikan.');
        }

        // Pastikan belum ada request pengembalian sebelumnya
        if (ReturnRequest::where('order_id', $order->id)->exists()) {
            return back()->with('error', 'Anda sudah pernah mengajukan pengembalian untuk pesanan ini.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
            'proof_files' => 'required|array|max:3',
            'proof_files.*' => 'required|file|mimes:jpeg,png,jpg,mp4,mov|max:10240', // Max 10MB
        ]);

        $filePaths = [];
        if ($request->hasFile('proof_files')) {
            foreach ($request->file('proof_files') as $file) {
                $path = $file->store('returns', 'public');
                $filePaths[] = $path;
            }
        }

        ReturnRequest::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'proof_images' => $filePaths,
            'status' => 'pending', // pending, approved, rejected
        ]);

        return back()->with('success', 'Pengajuan pengembalian barang berhasil dikirim dan sedang ditinjau oleh Admin.');
    }
}
