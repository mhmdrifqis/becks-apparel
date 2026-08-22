<?php

namespace App\Filament\Resources\UserAddressResource\Pages;

use App\Filament\Resources\UserAddressResource;
use App\Models\UserAddress;
use Filament\Resources\Pages\CreateRecord;

class CreateUserAddress extends CreateRecord
{
    protected static string $resource = UserAddressResource::class;

    protected function beforeCreate(): void
    {
        // Unset previous default address if this record is marked as default
        if ($this->data['is_default'] ?? false) {
            UserAddress::where('user_id', $this->data['user_id'])->update(['is_default' => false]);
        }
    }
}
