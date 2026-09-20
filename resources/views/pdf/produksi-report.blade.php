<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap Produksi</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin-bottom: 30px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #06402B; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px 6px; text-align: left; }
        th { background: #06402B; color: white; }
        
        @page {
            margin: 30px;
        }
        footer {
            position: fixed;
            bottom: -10px;
            left: 0px;
            right: 0px;
            height: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>
<body>
    <footer>
        Halaman <span class="pagenum"></span>
    </footer>
    <div class="header">
        <h2>Laporan Rekap Pengerjaan Produksi</h2>
        <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Order</th>
                <th>Tanggal Masuk</th>
                <th>Pemesan</th>
                <th>Total Qty (Pcs)</th>
                <th>Status Produksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $order)
                @php
                    $totalQty = $order->orderItems->sum('quantity');
                    $prodStatusText = match($order->status) {
                        'pending'   => 'Menunggu',
                        'paid'      => 'Antrian',
                        'printing'  => 'Cetak',
                        'sewing'    => 'Jahit',
                        'qc'        => 'QC',
                        'ready'     => 'Siap Kirim',
                        'shipped'   => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default     => ucfirst($order->status)
                    };
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>{{ $totalQty }} Pcs</td>
                    <td>{{ $prodStatusText }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
