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

    protected static ?string $navigationGroup = 'Ruang Produksi';

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
                                Infolists\Components\Grid::make(4)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('package.name')->label('Paket'),
                                        Infolists\Components\TextEntry::make('material.name')->label('Bahan'),
                                        Infolists\Components\TextEntry::make('quantity')->label('Qty (Pcs)'),
                                        Infolists\Components\TextEntry::make('material_usage')
                                            ->label('Pemakaian Bahan')
                                            ->formatStateUsing(fn ($state, $record) => $state ? ($state . ' ' . ($record->material?->unit ?? 'Meter')) : 'Belum dicatat')
                                            ->badge()
                                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                                    ]),
                                Infolists\Components\TextEntry::make('design.preview_path')
                                    ->label('Preview & Link Desain')
                                    ->html()
                                      ->formatStateUsing(function ($record) {
                                          if (!$record || !$record->design) return '<span class="text-sm text-gray-400 italic">Tidak ada desain terlampir</span>';
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

                                          if (empty($files)) {
                                              return '<span class="text-sm text-gray-400 italic">Tidak ada file desain</span>';
                                          }
                                          
                                          $html = '<div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-start">';
                                          $fallbackSvg = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="%239ca3af" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/><line x1="4" y1="4" x2="20" y2="20" stroke="%23ef4444" stroke-width="2"/></svg>';

                                          foreach($files as $i => $file) {
                                              $cleanPath = ltrim(str_replace(['public/', 'storage/'], '', $file), '/');
                                              $url = e(\Illuminate\Support\Facades\Storage::disk('public')->url($cleanPath));
                                              $filename = e(basename($file));
                                              $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                                              $isVideo = in_array($extension, ['mp4', 'mov', 'avi', 'webm'], true);
                                              $isVector = in_array($extension, ['cdr', 'ai', 'psd', 'eps', 'svg', 'pdf'], true);

                                              if ($isVideo) {
                                                  $preview = '<div style="display:flex;width:100px;height:100px;align-items:center;justify-content:center;border-radius:8px;background:#111827;color:#fff;font-size:11px;font-weight:700">VIDEO</div>';
                                              } elseif ($isVector) {
                                                  $preview = '<div style="display:flex;flex-direction:column;width:100px;height:100px;align-items:center;justify-content:center;border-radius:8px;background:#f8fafc;border:1px dashed #cbd5e1;color:#64748b;font-size:10px;font-weight:700;gap:4px">
                                                      <span style="font-size:16px;text-transform:uppercase;color:#ea580c">.' . e($extension) . '</span>
                                                      <span>File Desain</span>
                                                  </div>';
                                              } else {
                                                  $preview = '<div style="position:relative;width:100px;height:100px;background:#f1f5f9;border-radius:8px;overflow:hidden;display:flex;align-items:center;justify-content:center">
                                                      <img src="' . $url . '" 
                                                           alt="Desain ' . ($i + 1) . '" 
                                                           loading="lazy" 
                                                           onerror="this.onerror=null; this.src=\'' . $fallbackSvg . '\'; this.title=\'Gambar tidak dapat dimuat (404)\';" 
                                                           style="width:100%;height:100%;object-fit:cover;display:block">
                                                  </div>';
                                              }
                                              
                                              $html .= '<div style="width:118px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;padding:8px;box-shadow:0 1px 2px rgba(0,0,0,0.05);display:flex;flex-direction:column;align-items:center">
                                                  <a href="' . $url . '" target="_blank" rel="noopener noreferrer" title="Buka ukuran asli" style="display:block">
                                                      ' . $preview . '
                                                  </a>
                                                  <div style="width:100%;margin-top:6px;padding-top:4px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;font-size:10px">
                                                      <a href="' . $url . '" target="_blank" rel="noopener noreferrer" style="color:#2563eb;font-weight:600;text-decoration:none">Buka</a>
                                                      <a href="' . $url . '" download="' . $filename . '" style="color:#059669;font-weight:600;text-decoration:none">Unduh</a>
                                                  </div>
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

                Forms\Components\Section::make('Catat Pemakaian Bahan Baku')
                    ->description('Isi jumlah bahan yang terpakai untuk setiap item. Stok bahan baku di gudang otomatis terpotong saat disimpan.')
                    ->schema([
                        Forms\Components\Repeater::make('orderItems')
                            ->label('Daftar Item Pesanan')
                            ->relationship('orderItems')
                            ->schema([
                                Forms\Components\Placeholder::make('product_info')
                                    ->label('Item & Bahan')
                                    ->content(fn ($record) => $record ? ($record->package?->name . ' - ' . ($record->material?->name ?? 'Tanpa Bahan')) : '-'),

                                Forms\Components\Placeholder::make('qty_info')
                                    ->label('Jumlah')
                                    ->content(fn ($record) => $record ? ($record->quantity . ' pcs') : '-'),

                                Forms\Components\TextInput::make('material_usage')
                                    ->label('Pemakaian Bahan (Meter)')
                                    ->numeric()
                                    ->suffix(fn ($record) => $record?->material?->unit ?? 'Meter')
                                    ->placeholder('Contoh: 15.5')
                                    ->helperText('Otomatis memotong stok gudang'),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['user', 'orderItems.package', 'orderItems.material', 'orderItems.design'])
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
                    ->tooltip('Ubah Status & Catat Bahan')
                    ->iconButton()
                    ->slideOver(),
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
