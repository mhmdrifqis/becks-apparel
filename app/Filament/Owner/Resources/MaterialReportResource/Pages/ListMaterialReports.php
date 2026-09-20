<?php

namespace App\Filament\Owner\Resources\MaterialReportResource\Pages;

use App\Filament\Owner\Resources\MaterialReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaterialReports extends ListRecords
{
    protected static string $resource = MaterialReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
