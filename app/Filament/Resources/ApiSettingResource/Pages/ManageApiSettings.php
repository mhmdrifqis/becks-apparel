<?php

namespace App\Filament\Resources\ApiSettingResource\Pages;

use App\Filament\Resources\ApiSettingResource;
use App\Models\ApiSetting;
use App\Models\PaymentSetting;
use App\Helpers\ApiSettingHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class ManageApiSettings extends EditRecord
{
    protected static string $resource = ApiSettingResource::class;

    public function mount(int | string $record = null): void
    {
        $this->record = ApiSetting::instance();
        $this->fillForm();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $existing = ApiSetting::instance();

        $secretKeys = [
            'paywuz_sandbox_api_key',
            'paywuz_production_api_key',
            'rajaongkir_api_key',
            'fonnte_token',
            'gemini_api_key',
            'google_client_secret',
            'biteship_api_key',
            'smtp_password',
        ];

        foreach ($secretKeys as $key) {
            if (empty($data[$key]) && !empty($existing->{$key})) {
                $data[$key] = $existing->{$key};
            }
        }

        return $data;
    }

    protected function afterSave(): void
    {
        try {
            PaymentSetting::updateOrCreate([], [
                'is_active' => $this->record->paywuz_is_active,
                'environment' => $this->record->paywuz_environment,
                'sandbox_api_key' => $this->record->paywuz_sandbox_api_key,
                'production_api_key' => $this->record->paywuz_production_api_key,
            ]);
        } catch (\Exception $e) {}

        ApiSettingHelper::loadIntoConfig();

        Notification::make()
            ->title('Pengaturan API Berhasil Disimpan!')
            ->success()
            ->send();
    }
}
