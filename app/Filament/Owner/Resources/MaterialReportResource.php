<?php

namespace App\Filament\Owner\Resources;

use App\Filament\Owner\Resources\MaterialReportResource\Pages;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MaterialReportResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    
    protected static ?string $navigationGroup = 'Analitik & Laporan';
    
    protected static ?string $pluralModelLabel = 'Laporan Bahan Baku';
    
    protected static ?string $modelLabel = 'Bahan Baku';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->sortable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok Saat Ini')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->stock . ' ' . $record->unit),
                
                Tables\Columns\TextColumn::make('total_used')
                    ->label('Total Terpakai (Order)')
                    ->getStateUsing(function (Material $record) {
                        return $record->orderItems->filter(function($item) {
                            return in_array($item->order->payment_status ?? '', ['paid', 'partial']);
                        })->sum('quantity');
                    })
                    ->formatStateUsing(fn (string $state, $record) => $state . ' ' . $record->unit),
                
                Tables\Columns\BadgeColumn::make('status_stok')
                    ->label('Status')
                    ->getStateUsing(function (Material $record) {
                        if ($record->stock <= 0) return 'Habis';
                        if ($record->stock < 50) return 'Menipis';
                        return 'Aman';
                    })
                    ->colors([
                        'danger' => 'Habis',
                        'warning' => 'Menipis',
                        'success' => 'Aman',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                // No individual actions needed for a report
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('unduh_laporan_bahan')
                        ->label('Unduh Laporan (PDF)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, \Livewire\Component $livewire) {
                            $token = \Illuminate\Support\Str::random(10);
                            \Illuminate\Support\Facades\Cache::put('pdf_export_' . $token, $records, now()->addMinutes(5));
                            $url = route('pdf.preview_bulk', ['type' => 'materials', 'token' => $token]);
                            $livewire->js("window.open('{$url}', '_blank');");
                        }),
                ]),
            ])
            ->defaultSort('stock', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterialReports::route('/'),
        ];
    }
}
