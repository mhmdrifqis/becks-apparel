<?php

namespace App\Filament\Resources\ApiSettingResource\Pages;

use App\Filament\Resources\ApiSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageApiSettings extends ManageRecords
{
    protected static string $resource = ApiSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function () {
                    \App\Helpers\ApiSettingHelper::loadIntoConfig();
                }),
        ];
    }
}
