<?php

namespace App\Filament\Resources\LiveChatResource\Pages;

use App\Filament\Resources\LiveChatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLiveChat extends EditRecord
{
    protected static string $resource = LiveChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
