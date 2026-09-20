<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pesanan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin-bottom: 30px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #06402B; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px 6px; text-align: left; }
        th { background: #06402B; color: white; }
        .text-right { text-align: right; }
        
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
        <h2>Laporan Transaksi Pesanan</h2>
        <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Order</th>
                <th>Tanggal Masuk</th>
                <th>Pemesan</th>
                <th>Status Prod.</th>
                <th>Status Bayar</th>
                <th class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKeuangan = 0; @endphp
            @foreach($records as $index => $order)
                @php 
                    $totalKeuangan += $order->total_amount; 
                    
                    $payStatusText = match($order->payment_status) {
                        'paid' => 'Lunas',
                        'partial' => 'DP',
                        'unpaid' => 'Belum Bayar',
                        default => ucfirst($order->payment_status)
                    };
                    
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
                    <td>{{ $prodStatusText }}</td>
                    <td>{{ $payStatusText }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><strong>TOTAL PENDAPATAN</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalKeuangan, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
