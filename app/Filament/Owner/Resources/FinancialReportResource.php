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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Transaksi')
                    ->money('IDR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('deposit_amount')
                    ->label('Uang Muka')
                    ->money('IDR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR')),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Status Bayar')
                    ->colors([
                        'danger'  => 'unpaid',
                        'success' => 'paid',
                        'warning' => 'expired',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
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
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinancialReports::route('/'),
        ];
    }
}
