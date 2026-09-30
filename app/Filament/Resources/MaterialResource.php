<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Stok Bahan Baku';

    protected static ?string $modelLabel = 'Bahan Baku';

    protected static ?string $pluralModelLabel = 'Bahan Baku';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Bahan Baku')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Bahan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('additional_price')
                            ->label('Harga Tambahan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('stock')
                            ->label('Stok Tersedia')
                            ->numeric()
                            ->default(0),

                        Forms\Components\TextInput::make('unit')
                            ->label('Satuan')
                            ->placeholder('Meter, Roll, dll')
                            ->default('Meter')
                            ->required(),
                            
                        Forms\Components\CheckboxList::make('product_types')
                            ->label('Berlaku untuk Jenis Produk')
                            ->options([
                                'jersey'  => 'Jersey',
                                'jacket'  => 'Jaket',
                                'tshirt'  => 'Kaos (T-Shirt)',
                                'kemeja'  => 'Kemeja',
                            ])
                            ->helperText('Kosongkan = berlaku untuk semua jenis produk')
                            ->columns(2)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat (Opsional)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
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

                Tables\Columns\TextColumn::make('additional_price')
                    ->label('Harga Tambahan')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->formatStateUsing(function ($state, $record) {
                        $val = (float) $state;
                        $formatted = ($val == (int)$val) ? number_format($val, 0, ',', '.') : number_format($val, 2, ',', '.');
                        return $formatted . ' ' . $record->unit;
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_types')
                    ->label('Filter Jenis Produk')
                    ->options([
                        'jersey' => 'Jersey',
                        'jacket' => 'Jaket',
                        'tshirt' => 'Kaos (T-Shirt)',
                        'kemeja' => 'Kemeja',
                    ])
                    ->query(fn ($query, $data) => $data['value'] ? $query->whereJsonContains('product_types', $data['value']) : $query),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->tooltip('Lihat')
                    ->iconButton()
                    ->slideOver()
                    ->modalCancelAction(false),
                Tables\Actions\EditAction::make()
                    ->tooltip('Ubah')
                    ->iconButton()
                    ->slideOver(),
                Tables\Actions\DeleteAction::make()
                    ->tooltip('Hapus')
                    ->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterials::route('/'),
        ];
    }
}
