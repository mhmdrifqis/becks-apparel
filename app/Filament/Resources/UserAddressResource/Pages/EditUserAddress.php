<?php

namespace App\Filament\Resources\UserAddressResource\Pages;

use App\Filament\Resources\UserAddressResource;
use App\Models\UserAddress;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserAddress extends EditRecord
{
    protected static string $resource = UserAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        if ($this->data['is_default'] ?? false) {
            UserAddress::where('user_id', $this->data['user_id'])
                ->where('id', '!=', $this->record->id)
                ->update(['is_default' => false]);
        }
    }
}
