<?php

namespace App\Filament\Produksi\Resources;

use App\Filament\Produksi\Resources\OrderProduksiResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class OrderProduksiResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Manajemen Produksi';

    protected static ?string $modelLabel = 'Pesanan Produksi';

    protected static ?string $pluralModelLabel = 'Manajemen Produksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Workshop';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Pesanan')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_number')->label('No. Order'),
                        Infolists\Components\TextEntry::make('user.name')->label('Nama Pemesan'),
                        Infolists\Components\TextEntry::make('status')->badge(),
                        Infolists\Components\TextEntry::make('notes')->label('Catatan Pesanan')->columnSpanFull(),
                    ])->columns(3),

                Infolists\Components\Section::make('Detail Teknis & Desain')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('orderItems')
                            ->label('Daftar Produk')
                            ->schema([
                                Infolists\Components\Grid::make(3)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('package.name')->label('Paket'),
                                        Infolists\Components\TextEntry::make('material.name')->label('Bahan'),
                                        Infolists\Components\TextEntry::make('quantity')->label('Qty'),
                                    ]),
                                Infolists\Components\TextEntry::make('design.preview_path')
                                    ->label('Preview & Link Desain')
                                    ->html()
                                      ->formatStateUsing(function ($record) {
                                          if (!$record || !$record->design) return 'Tidak ada desain';
                                          $design = $record->design;
                                          $files = [];
                                          if ($design->preview_path) {
                                              $files[] = $design->preview_path;
                                          }
                                          $json = $design->design_json;
                                          if (is_array($json) && isset($json['files'])) {
                                              foreach($json['files'] as $f) {
                                                  if (!in_array($f, $files)) {
                                                      $files[] = $f;
                                                  }
                                              }
                                          } elseif (is_array($json) && isset($json['file'])) {
                                              if (!in_array($json['file'], $files)) {
                                                  $files[] = $json['file'];
                                              }
                                          }
                                          
                                          $html = '<div style="display:flex;flex-wrap:wrap;gap:12px">';
                                          foreach($files as $i => $file) {
                                              $url = e(\Illuminate\Support\Facades\Storage::url($file));
                                              $filename = e(basename($file));
                                              $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                              
                                              $preview = in_array($extension, ['mp4', 'mov', 'avi', 'webm'], true) 
                                                  ? '<div style="display:flex;width:100px;height:100px;align-items:center;justify-content:center;border-radius:8px;background:#111827;color:#fff;font-size:11px">Video</div>' 
                                                  : '<img src="' . $url . '" alt="Desain ' . ($i + 1) . '" style="display:block;width:100px;height:100px;object-fit:cover;border-radius:8px">';
                                                  
                                              $html .= '<div style="width:124px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;padding:8px">
                                                  <a href="' . $url . '" target="_blank" rel="noopener" title="Buka ukuran asli">' . $preview . '</a>
                                                  <a href="' . $url . '" download="Desain_Becks_'.$i.'.jpg" style="display:block;text-align:center;margin-top:6px;font-size:10px;font-weight:600;color:#4b5563">Unduh</a>
                                              </div>';
                                          }
                                          $html .= '</div>';
                                          return new \Illuminate\Support\HtmlString($html);
                                      }),
                            ])
                    ])
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produksi')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('No. Order')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Pembaruan Proses Produksi')
                            ->options([
                                'paid'      => 'Antrian Masuk',
                                'printing'  => 'Proses Cetak',
                                'sewing'    => 'Proses Jahit',
                                'qc'        => 'Quality Control',
                                'ready'     => 'Selesai Produksi (Siap Kirim)',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->whereIn('payment_status', ['paid', 'partial'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemesan')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status Produksi')
                    ->colors([
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
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
                Tables\Filters\SelectFilter::make('status')
                    ->label('Tahapan')
                    ->options([
                        'paid'     => 'Antrian',
                        'printing' => 'Cetak',
                        'sewing'   => 'Jahit',
                        'qc'       => 'QC',
                        'ready'    => 'Selesai',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('until')->label('Hingga Tanggal'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('unduh_spk')
                    ->label('Unduh SPK')
                    ->tooltip('Unduh SPK')
                    ->iconButton()
                    ->icon('heroicon-o-printer')
                    ->url(fn (\App\Models\Order $record): string => route('download.spk', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->tooltip('Lihat Detail')
                    ->iconButton()
                    ->slideOver()
                    ->modalCancelAction(false),
                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->tooltip('Ubah Status Produksi')
                    ->iconButton()
                    ->slideOver(),
                
                // FITUR: Catat Pemakaian Bahan
                Tables\Actions\Action::make('log_material_usage')
                    ->label('Log Bahan')
                    ->tooltip('Catat Pemakaian Bahan Baku')
                    ->iconButton()
                    ->icon('heroicon-m-beaker')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('order_item_id')
                            ->label('Pilih Produk (Item)')
                            ->options(fn ($record) => $record->orderItems->mapWithKeys(function ($item) {
                                return [$item->id => $item->package->name . ' (' . $item->material->name . ')'];
                            }))
                            ->required(),
                        Forms\Components\TextInput::make('material_used')
                            ->label('Jumlah Bahan Dipakai')
                            ->numeric()
                            ->suffix('Satuan')
                            ->required()
                            ->helperText('Contoh: 2 (Jika memakai 2 kg bahan)'),
                    ])
                    ->action(function (array $data, \App\Models\Order $record) {
                        $orderItem = \App\Models\OrderItem::find($data['order_item_id']);
                        $material = $orderItem->material;
                        
                        if ($material) {
                            $material->decrement('stock', $data['material_used']);
                            
                            $orderItem->update([
                                'material_usage' => $data['material_used']
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Bahan baku berhasil dipotong dari gudang')
                                ->success()
                                ->send();
                        }
                    })
                    ->modalHeading('Input Pemakaian Bahan Baku')
                    ->modalSubmitActionLabel('Potong Stok Sekarang'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('unduh_laporan_pdf')
                        ->label('Unduh Laporan (PDF)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, \Livewire\Component $livewire) {
                            $token = \Illuminate\Support\Str::random(10);
                            \Illuminate\Support\Facades\Cache::put('pdf_export_' . $token, $records, now()->addMinutes(5));
                            $url = route('pdf.preview_bulk', ['type' => 'produksi', 'token' => $token]);
                            $livewire->js("window.open('{$url}', '_blank');");
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relation manager untuk daftar item (ukuran, desain) akan ditambahkan nanti
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderProduksi::route('/'),
        ];
    }
}
