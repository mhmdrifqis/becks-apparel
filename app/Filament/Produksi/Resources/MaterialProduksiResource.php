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
    protected static ?string $navigationGroup = 'Ruang Produksi';
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
                Tables\Columns\TextColumn::make('product_types')
                    ->label('Jenis Produk')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $types = $record->product_types;
                        return (empty($types) || !is_array($types)) ? ['Semua'] : $types;
                    })
                    ->colors([
                        'primary' => 'jersey',
                        'info'    => 'jacket',
                        'warning' => 'tshirt',
                        'success' => 'kemeja',
                        'gray'    => 'Semua',
                    ])
                    ->formatStateUsing(fn ($state) => match(strtolower($state)) {
                        'jersey' => 'Jersey',
                        'jacket' => 'Jaket',
                        'tshirt' => 'Kaos',
                        'kemeja' => 'Kemeja',
                        default  => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Sisa Stok')
                    ->formatStateUsing(function ($state) {
                        $val = (float) $state;
                        return ($val == (int)$val) ? number_format($val, 0, ',', '.') : number_format($val, 2, ',', '.');
                    })
                    ->color(fn ($state) => $state < 50 ? 'danger' : 'success')
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Satuan')
                    ->badge()
                    ->color('gray'),
            ])
            ->defaultSort('stock', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('product_types')
                    ->label('Filter Jenis Produk')
                    ->options([
                        'jersey' => 'Jersey',
                        'jacket' => 'Jaket',
                        'tshirt' => 'Kaos (T-Shirt)',
                        'kemeja' => 'Kemeja',
                    ])
                    ->query(fn ($query, $data) => !empty($data['value']) ? $query->whereJsonContains('product_types', $data['value']) : $query),
            ])
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
