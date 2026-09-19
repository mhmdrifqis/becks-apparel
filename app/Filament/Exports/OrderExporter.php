<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('order_number')->label('No. Pesanan'),
            ExportColumn::make('user.name')->label('Nama Pelanggan'),
            ExportColumn::make('total_amount')->label('Total Harga (Rp)'),
            ExportColumn::make('deposit_amount')->label('Uang Muka (Rp)'),
            ExportColumn::make('payment_status')->label('Status Pembayaran'),
            ExportColumn::make('status')->label('Status Operasional'),
            ExportColumn::make('payment_gateway_id')->label('Referensi Gateway'),
            ExportColumn::make('shipping_awb')->label('No. Resi'),
            ExportColumn::make('created_at')->label('Tanggal Transaksi'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor Laporan Transaksi telah selesai dan ' . number_format($export->successful_rows) . ' baris data berhasil diunduh.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
