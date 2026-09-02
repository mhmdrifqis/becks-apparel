<?php

namespace App\Filament\Produksi\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::whereIn('payment_status', ['paid', 'partial'])
                    ->whereIn('status', ['paid'])
                    ->latest()
                    ->limit(5)
            )
            ->heading('Pesanan Baru (Perlu Diproses)')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Order')
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemesan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Masuk')
                    ->dateTime('d M Y H:i'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors(['warning' => 'paid'])
                    ->formatStateUsing(fn () => 'Menunggu Cetak'),
            ])
            ->actions([
                Tables\Actions\Action::make('kerjakan')
                    ->label('Mulai Proses')
                    ->button()
                    ->url(fn (Order $record): string => \App\Filament\Produksi\Resources\OrderProduksiResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
