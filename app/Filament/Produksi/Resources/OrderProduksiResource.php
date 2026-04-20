<?php

namespace App\Filament\Produksi\Resources;

use App\Filament\Produksi\Resources\OrderProduksiResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderProduksiResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Antrean Produksi';

    protected static ?string $modelLabel = 'Pesanan Produksi';

    protected static ?string $pluralModelLabel = 'Antrean Produksi';

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
                            ->label('Update Progress Produksi')
                            ->options([
                                'paid'      => 'Antrean Masuk',
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
                    ->whereIn('status', ['paid', 'printing', 'sewing', 'qc', 'ready'])
                    ->where('payment_status', 'paid')
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
                    ->label('Status Saat Ini')
                    ->colors([
                        'success' => 'paid',
                        'info'    => 'printing',
                        'warning' => 'sewing',
                        'warning' => 'qc',
                        'primary' => 'ready',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid'     => 'Antrean',
                        'printing' => 'Cetak',
                        'sewing'   => 'Jahit',
                        'qc'       => 'QC',
                        'ready'    => 'Selesai',
                        default    => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Bayar')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Tahapan')
                    ->options([
                        'paid'     => 'Antrean',
                        'printing' => 'Cetak',
                        'sewing'   => 'Jahit',
                        'qc'       => 'QC',
                        'ready'    => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat Detail Teknis'),
                Tables\Actions\EditAction::make()->label('Update Status'),
            ])
            ->bulkActions([]);
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
            'view' => Pages\ViewOrderProduksi::route('/{record}'),
            'edit' => Pages\EditOrderProduksi::route('/{record}/edit'),
        ];
    }
}
