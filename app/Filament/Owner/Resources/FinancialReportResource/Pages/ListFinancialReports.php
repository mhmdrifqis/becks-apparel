<?php

namespace App\Filament\Owner\Resources\FinancialReportResource\Pages;

use App\Filament\Owner\Resources\FinancialReportResource;
use Filament\Resources\Pages\ListRecords;

class ListFinancialReports extends ListRecords
{
    protected static string $resource = FinancialReportResource::class;

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
