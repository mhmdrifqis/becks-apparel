<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\FinancialReportResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class FinancialReportResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Laporan Keuangan';

    protected static ?string $modelLabel = 'Laporan Transaksi';

    protected static ?string $pluralModelLabel = 'Laporan Transaksi';

    protected static ?string $navigationGroup = 'Analitik & Laporan';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemesan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR'))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->colors([
                        'danger'  => 'unpaid',
                        'success' => 'paid',
                        'warning' => 'partial',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid'  => 'Belum Bayar',
                        'partial' => 'DP',
                        'paid'    => 'Lunas',
                        default   => ucfirst($state),
                    }),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status Produksi')
                    ->colors([
                        'gray'    => 'pending',
                        'success' => 'paid',
                        'info'    => 'printing',
                        'warning' => 'sewing',
                        'danger'  => 'qc',
                        'primary' => 'ready',
                        'gray'    => 'completed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Menunggu',
                        'paid'      => 'Antrian',
                        'printing'  => 'Cetak',
                        'sewing'    => 'Jahit',
                        'qc'        => 'QC',
                        'ready'     => 'Siap Kirim',
                        'shipped'   => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default     => ucfirst($state),
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('periode')
                    ->label('Filter Rentang Waktu (Otomatis)')
                    ->options([
                        'hari_ini' => 'Hari Ini',
                        'minggu_ini' => 'Minggu Ini',
                        'bulan_ini' => 'Bulan Ini',
                        'tahun_ini' => 'Tahun Ini',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'hari_ini') {
                            $query->whereDate('created_at', now()->toDateString());
                        } elseif ($data['value'] === 'minggu_ini') {
                            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        } elseif ($data['value'] === 'bulan_ini') {
                            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        } elseif ($data['value'] === 'tahun_ini') {
                            $query->whereYear('created_at', now()->year);
                        }
                    }),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Hingga Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Produksi')
                    ->options([
                        'pending'   => 'Menunggu',
                        'paid'      => 'Antrian Masuk',
                        'printing'  => 'Cetak',
                        'sewing'    => 'Jahit',
                        'qc'        => 'QC',
                        'ready'     => 'Siap Kirim',
                        'shipped'   => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'unpaid'  => 'Belum Bayar',
                        'partial' => 'DP',
                        'paid'    => 'Lunas',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('unduh_invoice')
                    ->label('Unduh Invoice')
                    ->tooltip('Unduh Invoice')
                    ->iconButton()
                    ->icon('heroicon-o-printer')
                    ->url(fn (Order $record): string => route('download.invoice', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make()
                    ->tooltip('Lihat Detail')
                    ->iconButton()
                    ->slideOver()
                    ->modalCancelAction(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('unduh_laporan_pdf')
                        ->label('Unduh Laporan (PDF)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, \Livewire\Component $livewire) {
                            $token = \Illuminate\Support\Str::random(10);
                            \Illuminate\Support\Facades\Cache::put('pdf_export_' . $token, $records, now()->addMinutes(5));
                            $url = route('pdf.preview_bulk', ['type' => 'orders', 'token' => $token]);
                            $livewire->js("window.open('{$url}', '_blank');");
                        }),
                ]),
            ]);
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('order_number')->label('No. Order'),
                        \Filament\Infolists\Components\TextEntry::make('user.name')->label('Pemesan'),
                        \Filament\Infolists\Components\TextEntry::make('created_at')->label('Tanggal')->dateTime('d M Y H:i'),
                        \Filament\Infolists\Components\TextEntry::make('total_amount')->label('Total Harga')->money('IDR'),
                        \Filament\Infolists\Components\TextEntry::make('payment_status')->label('Status Pembayaran')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'unpaid'  => 'Belum Bayar',
                                'partial' => 'DP',
                                'paid'    => 'Lunas',
                                default   => ucfirst($state),
                            })
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'unpaid' => 'danger',
                                'partial' => 'warning',
                                'paid' => 'success',
                                default => 'gray',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('status')->label('Status Produksi')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending'   => 'Menunggu',
                                'paid'      => 'Antrian Masuk',
                                'printing'  => 'Cetak',
                                'sewing'    => 'Jahit',
                                'qc'        => 'QC',
                                'ready'     => 'Siap Kirim',
                                'shipped'   => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default     => ucfirst($state),
                            })
                            ->badge(),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinancialReports::route('/'),
        ];
    }
}
