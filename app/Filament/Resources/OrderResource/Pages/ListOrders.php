<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('unduh_seluruh_laporan_pdf')
                ->label('Unduh Seluruh Laporan (PDF)')
                ->color('primary')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function (\Livewire\Component $livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();
                    $token = \Illuminate\Support\Str::random(10);
                    \Illuminate\Support\Facades\Cache::put('pdf_export_' . $token, $records, now()->addMinutes(5));
                    $url = route('pdf.preview_bulk', ['type' => 'orders', 'token' => $token]);
                    $livewire->js("window.open('{$url}', '_blank');");
                }),
        ];
    }
}
