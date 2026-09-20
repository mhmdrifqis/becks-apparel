<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #06402B;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .header h1 {
            color: #06402B;
            margin: 0;
            font-size: 28px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .items-table th {
            background-color: #06402B;
            color: white;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .total-row td {
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            background-color: #06402B;
        }
        @page { margin: 30px; }
        .page-footer {
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
    <div class="page-footer">
        Halaman <span class="pagenum"></span>
    </div>

    <div class="header">
        <table>
            <tr>
                <td>
                    @php
                        echo '<h1 style="margin:0; color:#06402B;">BECKS APPAREL</h1>';
                    @endphp

                   <p>
                    Komplek Villa Bintaro Indah Blok D2 Nomor 18,<br>
                    Jalan Halmahera, Jombang, Ciputat,<br>
                    Tangerang Selatan
                    </p>

                </td>
                <td class="text-right" style="text-align: right;">
                    <h2 style="margin:0; color:#555;">INVOICE</h2>
                    <p>
                        <strong>No. Order:</strong><br> {{ $order->order_number }}<br>
                        <strong>Tanggal:</strong><br> {{ $order->created_at->format('d M Y H:i') }}<br>
                        @php
                            $payStatusText = match($order->payment_status) {
                                'paid' => 'LUNAS',
                                'partial' => 'DP',
                                'unpaid' => 'BELUM BAYAR',
                                default => strtoupper($order->payment_status)
                            };
                        @endphp
                        <strong>Status:</strong><br> <span class="badge">{{ $payStatusText }}</span>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <strong>Kepada Yth:</strong><br>
                {{ $order->user->name }}<br>
                {{ $order->user->email }}<br>
                {{ $order->user->phone ?? '' }}
            </td>
            <td class="text-right">
                <strong>Metode Pengiriman:</strong><br>
                {{ strtoupper($order->shipping_service) }} - {{ $order->courier_name }}<br>
                @php
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
                <strong>Status Produksi:</strong><br> {{ $prodStatusText }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item / Produk</th>
                <th>Ukuran</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    <strong>{{ $item->package->name }}</strong><br>
                    <small>Bahan: {{ $item->material->name ?? '-' }}</small>
                    @if($item->upgrades->count() > 0)
                        <br><small>Tambahan: {{ $item->upgrades->pluck('name')->join(', ') }}</small>
                    @endif
                </td>
                <td>
                    @php
                        $sizeCounts = [];
                        if (is_array($item->roster)) {
                            foreach($item->roster as $r) {
                                $size = $r['size'] ?? 'All Size';
                                if(!isset($sizeCounts[$size])) {
                                    $sizeCounts[$size] = 0;
                                }
                                $sizeCounts[$size]++;
                            }
                        }
                    @endphp
                    
                    @if(!empty($sizeCounts))
                        @foreach($sizeCounts as $size => $count)
                            <div style="font-size:11px;"><strong>{{ $size }}</strong></div>
                        @endforeach
                    @else
                        <small>-</small>
                    @endif
                </td>
                <td class="text-right">{{ $item->quantity }} pcs</td>
                <td class="text-right">
                    @php
                        // Hitung harga dasar + material tambahan
                        $basePrice = $item->package->base_price + ($item->material->additional_price ?? 0);
                        // Hitung total upgrade
                        $upgradeTotal = $item->upgrades->sum('price');
                        $itemTotal = ($basePrice + $upgradeTotal) * $item->quantity;
                    @endphp
                    Rp {{ number_format($itemTotal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
            
            <tr>
                <td colspan="3" class="text-right"><strong>Biaya Pengiriman</strong></td>
                <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>TOTAL KESELURUHAN</strong></td>
                <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if($order->notes)
    <div style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-left: 4px solid #06402B;">
        <strong>Catatan Pesanan:</strong><br>
        {{ $order->notes }}
    </div>
    @endif

    <div class="footer">
        Terima kasih atas kepercayaan Anda memesan di Becks Apparel.<br>
        Invoice ini sah dan digenerate otomatis oleh sistem komputer.
    </div>

</body>
</html>
