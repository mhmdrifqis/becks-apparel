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
                            ->label('Pelanggan')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Status Produksi')
                            ->options([
                                'pending'   => 'Pending',
                                'paid'      => 'Antrean Masuk',
                                'printing'  => 'Proses Cetak',
                                'sewing'    => 'Proses Jahit',
                                'qc'        => 'Quality Control',
                                'ready'     => 'Siap Kirim',
                                'shipped'   => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->required(),

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
                                    ->content(fn ($record) => $record && $record->design ? 
                                        new \Illuminate\Support\HtmlString('
                                            <div class="space-y-2">
                                                <img src="' . Storage::url($record->design->preview_path) . '" class="w-full max-w-[200px] rounded-xl border border-slate-200 shadow-sm" />
                                                <a href="' . Storage::url($record->design->preview_path) . '" target="_blank" class="inline-flex items-center text-xs font-bold text-primary-600 hover:underline">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    Download File Asli
                                                </a>
                                            </div>
                                        ') : 
                                        'Tidak ada file desain.'
                                    ),
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
                        default   => $state,
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Pending',
                        'paid'      => 'Antrean Masuk',
                        'printing'  => 'Cetak',
                        'sewing'    => 'Jahit',
                        'qc'        => 'QC',
                        'ready'     => 'Siap Kirim',
                        'shipped'   => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default     => $state,
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
                    ->dateTime('d M Y')
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Produksi')
                    ->options([
                        'pending'   => 'Pending',
                        'paid'      => 'Antrean Masuk',
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
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->slideOver()
                    ->modalWidth(\Filament\Support\Enums\MaxWidth::ExtraLarge),
                Tables\Actions\EditAction::make()
                    ->label('Update')
                    ->slideOver()
                    ->modalWidth(\Filament\Support\Enums\MaxWidth::ExtraLarge),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'view'   => Pages\ViewOrder::route('/{record}'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
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
