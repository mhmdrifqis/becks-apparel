<?php

namespace App\Filament\Resources\LiveChatResource\Pages;

use App\Filament\Resources\LiveChatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLiveChat extends ViewRecord
{
    protected static string $resource = LiveChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }
}
