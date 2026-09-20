<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SPK {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111;
            font-size: 13px;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #06402B;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            color: #06402B;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ccc;
        }
        .items-table th {
            background-color: #06402B;
            color: white;
            font-weight: bold;
        }
        .roster-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 11px;
        }
        .roster-table th, .roster-table td {
            border: 1px solid #ccc;
            padding: 4px;
        }
        .roster-table th {
            background: #06402B;
            color: white;
        }
        .notes-box {
            border: 2px dashed #06402B;
            padding: 15px;
            margin-top: 20px;
            background: #fafafa;
        }
        
        @page { margin: 30px; }
        footer {
            position: fixed;
            bottom: -10px;
            left: 0px;
            right: 0px;
            height: 20px;
            text-align: right;
            font-size: 10px;
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
        <h1>SURAT PERINTAH KERJA (SPK) PRODUKSI</h1>
        <p style="margin:5px 0 0 0;"><strong>No. Order:</strong> {{ $order->order_number }} | <strong>Tgl Masuk:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <strong>Nama Pemesan:</strong> {{ $order->user->name }}<br>
                <strong>Kontak:</strong> {{ $order->user->email }}
            </td>
            <td>
                <strong>Estimasi Pengiriman:</strong> {{ strtoupper($order->shipping_service) }} - {{ $order->courier_name }}<br>
                <strong>Desain Utama:</strong> 
                @if($order->design && $order->design->preview_path)
                    Terkonfirmasi (Ada)
                @else
                    Tunggu Konfirmasi Admin
                @endif
            </td>
        </tr>
    </table>

    <h3 style="margin-bottom: 5px;">Rincian Pengerjaan</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Paket & Bahan</th>
                <th>Detail Tambahan (Upgrade)</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->package->name }}</strong><br>
                    Bahan: {{ $item->material->name ?? '-' }}
                </td>
                <td>
                    {{ $item->upgrades->count() > 0 ? $item->upgrades->pluck('name')->join(', ') : '-' }}
                </td>
                <td><strong>{{ $item->quantity }} pcs</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-bottom: 5px;">Roster & Ukuran (Size Chart)</h3>
    @foreach($order->orderItems as $item)
        @if(is_array($item->roster) && count($item->roster) > 0)
        <div style="margin-bottom: 15px;">
            <strong>Item: {{ $item->package->name }}</strong> (Total: {{ $item->quantity }})
            <table class="roster-table">
                <thead>
                    <tr>
                        <th>Nama (Punggung)</th>
                        <th>Nomor</th>
                        <th>Ukuran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->roster as $r)
                    <tr>
                        <td>{{ $r['name'] ?? '-' }}</td>
                        <td>{{ $r['number'] ?? '-' }}</td>
                        <td><strong>{{ $r['size'] ?? '-' }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @endforeach

    @if($order->notes)
    <div class="notes-box">
        <strong>CATATAN KHUSUS (Harap Dibaca!):</strong><br>
        {{ $order->notes }}
    </div>
    @endif

</body>
</html>
