<?php

namespace App\Filament\Resources\PaymentSettingResource\Pages;

use App\Filament\Resources\PaymentSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentSettings extends ManageRecords
{
    protected static string $resource = PaymentSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function ($record) {
                    try {
                        $apiSetting = \App\Models\ApiSetting::instance();
                        $apiSetting->update([
                            'paywuz_is_active' => $record->is_active,
                            'paywuz_environment' => $record->environment,
                            'paywuz_sandbox_api_key' => $record->sandbox_api_key,
                            'paywuz_production_api_key' => $record->production_api_key,
                        ]);
                    } catch (\Exception $e) {}

                    \App\Helpers\ApiSettingHelper::loadIntoConfig();
                }),
        ];
    }
}
