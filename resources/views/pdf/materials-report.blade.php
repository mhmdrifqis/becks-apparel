<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bahan Baku</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin-bottom: 30px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #06402B; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px 6px; text-align: left; }
        th { background: #06402B; color: white; }
        .text-center { text-align: center; }
        .badge { padding: 3px 6px; border-radius: 4px; font-weight: bold; color: white; }
        .bg-green { background: #16a34a; }
        .bg-yellow { background: #ca8a04; }
        .bg-red { background: #dc2626; }
        
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
        <h2>Laporan Analitik Bahan Baku</h2>
        <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bahan</th>
                <th>Kategori</th>
                <th class="text-center">Stok Saat Ini</th>
                <th class="text-center">Total Pemakaian (Order)</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $material)
                @php
                    // Menghitung total pemakaian dari relasi orderItems (hanya order yang lunas/dp)
                    $totalUsed = $material->orderItems->filter(function($item) {
                        return in_array($item->order->payment_status ?? '', ['paid', 'partial']);
                    })->sum('quantity');
                    
                    $statusClass = 'bg-green';
                    $statusText = 'Aman';
                    if ($material->stock <= 0) {
                        $statusClass = 'bg-red';
                        $statusText = 'Habis';
                    } elseif ($material->stock < 50) {
                        $statusClass = 'bg-yellow';
                        $statusText = 'Menipis';
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $material->name }}</td>
                    <td>{{ ucfirst($material->category) }}</td>
                    <td class="text-center">{{ $material->stock }} {{ $material->unit }}</td>
                    <td class="text-center">{{ $totalUsed }} {{ $material->unit }}</td>
                    <td class="text-center">
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
