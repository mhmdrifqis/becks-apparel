<?php

namespace App\Filament\Produksi\Resources\OrderProduksiResource\Pages;

use App\Filament\Produksi\Resources\OrderProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrderProduksi extends ViewRecord
{
    protected static string $resource = OrderProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Update Progress'),
        ];
    }
}
