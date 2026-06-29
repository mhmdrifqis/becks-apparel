<?php

namespace App\Filament\Owner\Resources\FinancialReportResource\Pages;

use App\Filament\Owner\Resources\FinancialReportResource;
use Filament\Resources\Pages\ListRecords;

class ListFinancialReports extends ListRecords
{
    protected static string $resource = FinancialReportResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
