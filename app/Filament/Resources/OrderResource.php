<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Manajemen Pesanan';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Pesanan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('No. Order')
                            ->disabled(),

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Pemesan')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending'   => 'Menunggu (Belum Bayar/DP)',
                                'paid'      => 'Antrian Masuk',
                                'printing'  => 'Proses Cetak',
                                'sewing'    => 'Proses Jahit',
                                'qc'        => 'Quality Control',
                                'ready'     => 'Siap Kirim',
                                'shipped'   => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Pesanan')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'unpaid'  => 'Belum Bayar',
                                'partial' => 'DP / Sebagian',
                                'paid'    => 'Lunas',
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Penerima & Pengiriman')
                    ->schema([
                        Forms\Components\TextInput::make('recipient_name')
                            ->label('Nama Penerima')
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('recipient_phone')
                            ->label('No. Telepon')
                            ->disabled(),
                            
                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Alamat Lengkap')
                            ->disabled()
                            ->columnSpanFull(),
                            
                        Forms\Components\TextInput::make('shipping_service')
                            ->label('Metode Kirim Pilihan Pelanggan')
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Biaya Kirim')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Keuangan')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Harga')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('deposit_amount')
                            ->label('Jumlah DP')
                            ->prefix('Rp')
                            ->numeric(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Pengiriman')
                    ->description('Isi data ini ketika pesanan siap dikirim')
                    ->schema([
                        Forms\Components\Select::make('courier_name')
                            ->label('Ekspedisi / Kurir')
                            ->options([
                                'JNE' => 'JNE',
                                'J&T' => 'J&T',
                                'Sicepat' => 'Sicepat',
                                'POS' => 'POS Indonesia',
                                'Wahana' => 'Wahana',
                                'Ninja' => 'Ninja Xpress',
                                'Lalamove' => 'Lalamove',
                                'Grab' => 'Grab Express',
                                'Gojek' => 'GoSend',
                                'Self Pickup' => 'Ambil Sendiri',
                            ])
                            ->searchable(),

                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->placeholder('Masukkan nomor resi pengiriman')
                            ->helperText('Notifikasi WA akan menyertakan nomor resi ini'),
                    ])
                    ->columns(2)
                    ->collapsed(fn ($record) => $record && $record->status !== 'shipped'),
                Forms\Components\Section::make('Rincian Produk')
                    ->description('Daftar paket dan roster pemain yang dipesan')
                    ->schema([
                        Forms\Components\Repeater::make('orderItems')
                            ->relationship()
                            ->label('Item Pesanan')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('package_id')
                                            ->relationship('package', 'name')
                                            ->label('Paket')
                                            ->disabled(),
                                        Forms\Components\Select::make('material_id')
                                            ->relationship('material', 'name')
                                            ->label('Bahan')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Qty (Pcs)')
                                            ->disabled(),
                                    ]),
                                
                                Forms\Components\CheckboxList::make('upgrades')
                                    ->relationship('upgrades', 'name')
                                    ->label('Ekstra Upgrade')
                                    ->columns(3)
                                    ->disabled(),

                                Forms\Components\Repeater::make('roster')
                                    ->label('Daftar Roster Pemain')
                                    ->schema([
                                        Forms\Components\Grid::make(4)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')->label('Nama'),
                                                Forms\Components\TextInput::make('number')->label('No'),
                                                Forms\Components\TextInput::make('size')->label('Size'),
                                                Forms\Components\Toggle::make('isLongSleeve')->label('Lengan Panjang'),
                                            ]),
                                    ])
                                    ->reorderable(false)
                                    ->addable(false)
                                    ->deletable(false)
                                    ->columnSpanFull(),
                                Forms\Components\Placeholder::make('design_preview')
                                    ->label('Desain & Referensi')
                                      ->content(function ($record) {
                                          if (!$record || !$record->design) return 'Tidak ada file desain.';
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
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                    ->color('primary'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->colors([
                        'danger'  => 'unpaid',
                        'warning' => 'partial',
                        'success' => 'paid',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid'  => 'Belum Bayar',
                        'partial' => 'DP',
                        'paid'    => 'Lunas',
                        default   => ucfirst($state),
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Menunggu',
                        'paid'      => 'Antrian Masuk',
                        'printing'  => 'Proses Cetak',
                        'sewing'    => 'Proses Jahit',
                        'qc'        => 'Quality Control',
                        'ready'     => 'Siap Kirim',
                        'shipped'   => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default     => ucfirst($state),
                    })
                    ->colors([
                        'gray'    => 'pending',
                        'success' => 'paid',
                        'info'    => 'printing',
                        'warning' => 'sewing',
                        'warning' => 'qc',
                        'primary' => 'ready',
                        'success' => 'shipped',
                        'success' => 'completed',
                        'danger'  => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
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
                Tables\Actions\Action::make('ship')
                    ->label('Kirim')
                    ->tooltip('Kirim Pesanan')
                    ->iconButton()
                    ->slideOver()
                    ->icon('heroicon-o-truck')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === 'ready')
                    ->form([
                        Forms\Components\Select::make('courier_name')
                            ->label('Ekspedisi')
                            ->options([
                                'JNE' => 'JNE',
                                'J&T' => 'J&T',
                                'Sicepat' => 'Sicepat',
                                'POS' => 'POS Indonesia',
                                'Wahana' => 'Wahana',
                                'Ninja' => 'Ninja Xpress',
                                'Lalamove' => 'Lalamove',
                                'Grab' => 'Grab Express',
                                'Gojek' => 'GoSend',
                                'Self Pickup' => 'Ambil Sendiri',
                            ])
                            ->default(fn ($record) => $record->courier_name)
                            ->required(),
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->placeholder('Contoh: JP1234567890')
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update([
                            'status' => 'shipped',
                            'courier_name' => $data['courier_name'],
                            'tracking_number' => $data['tracking_number'],
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan berhasil dikirim')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('complete')
                    ->label('Selesai')
                    ->tooltip('Selesaikan Pesanan')
                    ->iconButton()
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'shipped')
                    ->requiresConfirmation()
                    ->action(function (Order $record): void {
                        $record->update(['status' => 'completed']);
                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan telah diselesaikan')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->tooltip('Lihat Detail')
                    ->iconButton()
                    ->slideOver()
                    ->modalCancelAction(false)
                    ->modalWidth(\Filament\Support\Enums\MaxWidth::ExtraLarge),
                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->tooltip('Ubah Detail')
                    ->iconButton()
                    ->slideOver()
                    ->modalWidth(\Filament\Support\Enums\MaxWidth::ExtraLarge),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::whereIn('status', ['paid', 'printing', 'sewing', 'qc'])->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'warning';
    }
}
