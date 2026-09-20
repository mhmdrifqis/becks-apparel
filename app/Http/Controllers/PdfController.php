<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function invoice(Order $order)
    {
        // Pastikan hanya pemilik pesanan atau admin/owner yang bisa download
        if (Auth::id() !== $order->user_id && !Auth::user()->hasRole(['Admin', 'Management/Owner'])) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['user', 'orderItems.package', 'orderItems.material', 'orderItems.upgrades']);

        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        
        return $pdf->stream('Invoice_' . $order->order_number . '.pdf');
    }

    public function spk(Order $order)
    {
        // Pastikan hanya tim produksi atau admin/owner yang bisa download SPK
        if (!Auth::user()->hasRole(['Admin', 'Management/Owner', 'Tim Produksi'])) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['user', 'orderItems.package', 'orderItems.material', 'orderItems.upgrades']);

        $pdf = Pdf::loadView('pdf.spk', compact('order'));
        
        return $pdf->stream('SPK_' . $order->order_number . '.pdf');
    }

    public function bulkPreview(Request $request, $type, $token)
    {
        if (!Auth::check() || !Auth::user()->hasRole(['Admin', 'Management/Owner', 'Tim Produksi'])) {
            abort(403, 'Unauthorized action.');
        }

        $records = \Illuminate\Support\Facades\Cache::get('pdf_export_' . $token);
        if (!$records) {
            abort(404, 'Sesi cetak telah kedaluwarsa atau tidak valid.');
        }

        if ($type === 'produksi') {
            $pdf = Pdf::loadView('pdf.produksi-report', ['records' => $records]);
            return $pdf->stream('Laporan_Produksi.pdf');
        } elseif ($type === 'materials') {
            $pdf = Pdf::loadView('pdf.materials-report', ['records' => $records]);
            return $pdf->stream('Laporan_Bahan_Baku.pdf');
        } else {
            $pdf = Pdf::loadView('pdf.orders-report', ['records' => $records]);
            return $pdf->stream('Laporan_Pesanan.pdf');
        }
    }
}
