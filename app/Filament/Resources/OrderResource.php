<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

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
                                'paid'      => 'Lunas / Antrean',
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
                        'paid'      => 'Antrean',
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
                        'paid'      => 'Antrean',
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
                Tables\Actions\ViewAction::make()->label('Detail'),
                Tables\Actions\EditAction::make()->label('Update'),
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
