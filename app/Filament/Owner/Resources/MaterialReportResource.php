<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\MaterialReportResource\Pages;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MaterialReportResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    
    protected static ?string $navigationGroup = 'Analitik & Laporan';
    
    protected static ?string $pluralModelLabel = 'Laporan Bahan Baku';
    
    protected static ?string $modelLabel = 'Bahan Baku';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                
                Tables\Columns\TextColumn::make('product_types')
                    ->label('Jenis Produk')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $types = $record->product_types;
                        return (empty($types) || !is_array($types)) ? ['Semua'] : $types;
                    })
                    ->colors([
                        'primary' => 'jersey',
                        'info'    => 'jacket',
                        'warning' => 'tshirt',
                        'success' => 'kemeja',
                        'gray'    => 'Semua',
                    ])
                    ->formatStateUsing(fn ($state) => match(strtolower($state)) {
                        'jersey' => 'Jersey',
                        'jacket' => 'Jaket',
                        'tshirt' => 'Kaos',
                        'kemeja' => 'Kemeja',
                        default  => ucfirst($state),
                    }),
                
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok Saat Ini')
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        $val = (float) $state;
                        $formatted = ($val == (int)$val) ? number_format($val, 0, ',', '.') : number_format($val, 2, ',', '.');
                        return $formatted . ' ' . $record->unit;
                    }),
                
                Tables\Columns\TextColumn::make('total_used')
                    ->label('Total Terpakai (Order)')
                    ->getStateUsing(function (Material $record) {
                        return $record->orderItems->filter(function($item) {
                            return in_array($item->order->payment_status ?? '', ['paid', 'partial']);
                        })->sum(function($item) {
                            return $item->material_usage ?: $item->quantity;
                        });
                    })
                    ->formatStateUsing(function ($state, $record) {
                        $val = (float) $state;
                        $formatted = ($val == (int)$val) ? number_format($val, 0, ',', '.') : number_format($val, 2, ',', '.');
                        return $formatted . ' ' . $record->unit;
                    }),
                
                Tables\Columns\BadgeColumn::make('status_stok')
                    ->label('Status')
                    ->getStateUsing(function (Material $record) {
                        if ($record->stock <= 0) return 'Habis';
                        if ($record->stock < 50) return 'Menipis';
                        return 'Aman';
                    })
                    ->colors([
                        'danger' => 'Habis',
                        'warning' => 'Menipis',
                        'success' => 'Aman',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_types')
                    ->label('Filter Jenis Produk')
                    ->options([
                        'jersey' => 'Jersey',
                        'jacket' => 'Jaket',
                        'tshirt' => 'Kaos (T-Shirt)',
                        'kemeja' => 'Kemeja',
                    ])
                    ->query(fn ($query, $data) => !empty($data['value']) ? $query->whereJsonContains('product_types', $data['value']) : $query),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Lihat Detail Bahan')
                    ->iconButton()
                    ->slideOver()
                    ->modalCancelAction(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('unduh_laporan_bahan')
                        ->label('Unduh Laporan (PDF)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, \Livewire\Component $livewire) {
                            $token = \Illuminate\Support\Str::random(10);
                            \Illuminate\Support\Facades\Cache::put('pdf_export_' . $token, $records, now()->addMinutes(5));
                            $url = route('pdf.preview_bulk', ['type' => 'materials', 'token' => $token]);
                            $livewire->js("window.open('{$url}', '_blank');");
                        }),
                ]),
            ])
            ->defaultSort('stock', 'asc');
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Informasi Bahan Baku')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('name')
                            ->label('Nama Bahan')
                            ->weight(\Filament\Support\Enums\FontWeight::Bold),
                        \Filament\Infolists\Components\TextEntry::make('product_types')
                            ->label('Jenis Produk')
                            ->badge()
                            ->getStateUsing(fn ($record) => empty($record->product_types) ? ['Semua'] : $record->product_types)
                            ->colors([
                                'primary' => 'jersey',
                                'info'    => 'jacket',
                                'warning' => 'tshirt',
                                'success' => 'kemeja',
                                'gray'    => 'Semua',
                            ])
                            ->formatStateUsing(fn ($state) => match(strtolower($state)) {
                                'jersey' => 'Jersey',
                                'jacket' => 'Jaket',
                                'tshirt' => 'Kaos',
                                'kemeja' => 'Kemeja',
                                default  => ucfirst($state),
                            }),
                        \Filament\Infolists\Components\TextEntry::make('category')
                            ->label('Kategori')
                            ->formatStateUsing(fn ($state) => ucfirst($state ?? '-')),
                        \Filament\Infolists\Components\TextEntry::make('unit')
                            ->label('Satuan Unit')
                            ->default('Meter'),
                        \Filament\Infolists\Components\TextEntry::make('additional_price')
                            ->label('Biaya Tambahan / Upgrade')
                            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->label('Status Master Data')
                            ->formatStateUsing(fn ($state) => ($state === 'inactive' || $state === '0') ? 'Nonaktif' : 'Aktif')
                            ->badge()
                            ->color(fn ($state) => ($state === 'inactive' || $state === '0') ? 'danger' : 'success'),
                        \Filament\Infolists\Components\TextEntry::make('description')
                            ->label('Deskripsi Bahan')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada deskripsi'),
                    ])->columns(2),

                \Filament\Infolists\Components\Section::make('Analisis Stok & Penggunaan')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('stock')
                            ->label('Stok Saat Ini')
                            ->formatStateUsing(fn ($state, $record) => number_format($state ?? 0, 0, ',', '.') . ' ' . ($record->unit ?? 'Meter'))
                            ->weight(\Filament\Support\Enums\FontWeight::Bold),
                        \Filament\Infolists\Components\TextEntry::make('total_terpakai')
                            ->label('Total Terpakai (Order Masuk)')
                            ->getStateUsing(function (Material $record) {
                                $sum = $record->orderItems->filter(function($item) {
                                    return in_array($item->order->payment_status ?? '', ['paid', 'partial']);
                                })->sum(function($item) {
                                    return $item->material_usage ?: $item->quantity;
                                });
                                return number_format($sum, 0, ',', '.') . ' ' . ($record->unit ?? 'Meter');
                            }),
                        \Filament\Infolists\Components\TextEntry::make('status_ketersediaan')
                            ->label('Status Ketersediaan')
                            ->getStateUsing(function (Material $record) {
                                if ($record->stock <= 0) return 'Habis';
                                if ($record->stock < 50) return 'Menipis';
                                return 'Aman';
                            })
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Habis' => 'danger',
                                'Menipis' => 'warning',
                                'Aman' => 'success',
                                default => 'gray',
                            }),
                    ])->columns(3),

                \Filament\Infolists\Components\Section::make('Riwayat Pesanan yang Menggunakan Bahan Ini')
                    ->description('Daftar pesanan pelanggan terbaru yang menggunakan bahan ini')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('order_history')
                            ->label('')
                            ->html()
                            ->getStateUsing(function (Material $record) {
                                $items = $record->orderItems()
                                    ->with(['order.user', 'package'])
                                    ->latest()
                                    ->take(10)
                                    ->get();

                                if ($items->isEmpty()) {
                                    return '<p style="color: #6b7280; font-size: 13px; margin: 0;">Belum ada riwayat pesanan yang menggunakan bahan ini.</p>';
                                }

                                $html = '<div style="overflow-x: auto;"><table style="width: 100%; border-collapse: collapse; font-size: 12px; text-align: left;">';
                                $html .= '<thead style="border-bottom: 2px solid #e5e7eb; background: #f9fafb;">';
                                $html .= '<tr>';
                                $html .= '<th style="padding: 8px 10px; font-weight: 600;">No. Order</th>';
                                $html .= '<th style="padding: 8px 10px; font-weight: 600;">Pelanggan</th>';
                                $html .= '<th style="padding: 8px 10px; font-weight: 600;">Paket</th>';
                                $html .= '<th style="padding: 8px 10px; font-weight: 600; text-align: center;">Qty (Pcs)</th>';
                                $html .= '<th style="padding: 8px 10px; font-weight: 600; text-align: center;">Pemakaian</th>';
                                $html .= '<th style="padding: 8px 10px; font-weight: 600;">Status Order</th>';
                                $html .= '<th style="padding: 8px 10px; font-weight: 600;">Tanggal</th>';
                                $html .= '</tr></thead><tbody>';

                                foreach ($items as $item) {
                                    $order = $item->order;
                                    $orderNumber = e($order->order_number ?? '-');
                                    $userName = e($order->user->name ?? $order->recipient_name ?? '-');
                                    $packageName = e($item->package->name ?? '-');
                                    $qty = (int) $item->quantity;
                                    $usage = ($item->material_usage ?: $item->quantity) . ' ' . ($record->unit ?? 'Meter');
                                    
                                    $statusLabel = match ($order->status ?? '') {
                                        'pending'   => 'Menunggu',
                                        'paid'      => 'Antrian Masuk',
                                        'printing'  => 'Proses Cetak',
                                        'sewing'    => 'Proses Jahit',
                                        'qc'        => 'Quality Control',
                                        'ready'     => 'Siap Kirim',
                                        'shipped'   => 'Dikirim',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        default     => ucfirst($order->status ?? '-'),
                                    };

                                    $statusStyle = match ($order->status ?? '') {
                                        'completed' => 'background: #dcfce7; color: #166534;',
                                        'shipped', 'ready' => 'background: #e0e7ff; color: #3730a3;',
                                        'printing', 'sewing', 'qc' => 'background: #fef3c7; color: #92400e;',
                                        'paid' => 'background: #e0f2fe; color: #075985;',
                                        'cancelled' => 'background: #fee2e2; color: #991b1b;',
                                        default => 'background: #f3f4f6; color: #374151;',
                                    };

                                    $date = $order->created_at ? $order->created_at->format('d M Y') : '-';

                                    $html .= '<tr style="border-bottom: 1px solid #f3f4f6;">';
                                    $html .= '<td style="padding: 8px 10px; font-weight: 600; color: #111827;">' . $orderNumber . '</td>';
                                    $html .= '<td style="padding: 8px 10px;">' . $userName . '</td>';
                                    $html .= '<td style="padding: 8px 10px;">' . $packageName . '</td>';
                                    $html .= '<td style="padding: 8px 10px; text-align: center;">' . $qty . '</td>';
                                    $html .= '<td style="padding: 8px 10px; text-align: center; font-weight: 600;">' . $usage . '</td>';
                                    $html .= '<td style="padding: 8px 10px;"><span style="display:inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 600; ' . $statusStyle . '">' . $statusLabel . '</span></td>';
                                    $html .= '<td style="padding: 8px 10px; color: #6b7280;">' . $date . '</td>';
                                    $html .= '</tr>';
                                }

                                $html .= '</tbody></table></div>';
                                return $html;
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterialReports::route('/'),
        ];
    }
}
