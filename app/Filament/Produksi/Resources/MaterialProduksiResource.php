<?php

namespace App\Filament\Produksi\Resources;

use App\Models\Material;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaterialProduksiResource extends Resource
{
    protected static ?string $model = Material::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Workshop';
    protected static ?string $navigationLabel = 'Stok Bahan Baku';
    protected static ?string $pluralModelLabel = 'Bahan Baku';
    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool { return false; }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kualitas')
                    ->badge(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Sisa Stok')
                    ->numeric()
                    ->color(fn ($state) => $state < 50 ? 'danger' : 'success')
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Satuan')
                    ->color('gray'),
            ])
            ->defaultSort('stock', 'asc')
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Produksi\Resources\MaterialProduksiResource\Pages\ListMaterialProduksis::route('/'),
        ];
    }
}
