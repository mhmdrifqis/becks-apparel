<?php

namespace App\Filament\Produksi\Resources\OrderProduksiResource\Pages;

use App\Filament\Produksi\Resources\OrderProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrderProduksi extends ListRecords
{
    protected static string $resource = OrderProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        $antreanCount = \App\Models\Order::whereIn('status', ['paid', 'printing', 'sewing', 'qc', 'ready'])
                            ->whereIn('payment_status', ['paid', 'partial'])->count();

        return [
            'Semua' => Tab::make(),
            'Antrean' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['paid', 'printing', 'sewing', 'qc', 'ready']))
                ->badge($antreanCount > 0 ? $antreanCount : null)
                ->badgeColor('warning'),
            'Selesai' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['completed', 'shipped']))
                ->badgeColor('success'),
        ];
    }
}
