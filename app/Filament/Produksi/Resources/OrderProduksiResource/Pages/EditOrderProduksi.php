<?php

namespace App\Filament\Produksi\Resources\OrderProduksiResource\Pages;

use App\Filament\Produksi\Resources\OrderProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderProduksi extends EditRecord
{
    protected static string $resource = OrderProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
