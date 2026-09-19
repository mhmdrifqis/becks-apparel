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
            \Filament\Actions\ExportAction::make()
                ->exporter(\App\Filament\Exports\OrderExporter::class)
                ->color('primary')
                ->icon('heroicon-o-document-arrow-down')
                ->label('Unduh Seluruh Laporan (CSV)'),
        ];
    }
}
