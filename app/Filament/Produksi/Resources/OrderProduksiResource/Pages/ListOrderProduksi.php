<?php

namespace App\Filament\Produksi\Resources\OrderProduksiResource\Pages;

use App\Filament\Produksi\Resources\OrderProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrderProduksi extends ListRecords
{
    protected static string $resource = OrderProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('unduh_rekap_produksi_pdf')
                ->label('Unduh Rekap Produksi (PDF)')
                ->color('primary')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function (\Livewire\Component $livewire) {
                    $records = $livewire->getFilteredTableQuery()->get();
                    $token = \Illuminate\Support\Str::random(10);
                    \Illuminate\Support\Facades\Cache::put('pdf_export_' . $token, $records, now()->addMinutes(5));
                    $url = route('pdf.preview_bulk', ['type' => 'produksi', 'token' => $token]);
                    $livewire->js("window.open('{$url}', '_blank');");
                }),
        ];
    }

    public function getTabs(): array
    {
        $antrianCount = \App\Models\Order::whereIn('status', ['paid', 'printing', 'sewing', 'qc', 'ready'])
                            ->whereIn('payment_status', ['paid', 'partial'])->count();

        return [
            'Semua' => Tab::make(),
            'Antrian' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['paid', 'printing', 'sewing', 'qc', 'ready']))
                ->badge($antrianCount > 0 ? $antrianCount : null)
                ->badgeColor('warning'),
            'Selesai' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['completed', 'shipped']))
                ->badgeColor('success'),
        ];
    }
}
