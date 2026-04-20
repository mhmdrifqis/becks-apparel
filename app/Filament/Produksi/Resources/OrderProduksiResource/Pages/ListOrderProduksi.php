<?php

namespace App\Filament\Produksi\Resources\OrderProduksiResource\Pages;

use App\Filament\Produksi\Resources\OrderProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderProduksi extends ListRecords
{
    protected static string $resource = OrderProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
