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
                Forms\Components\Section::make('Informasi Bahan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Bahan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('category')
                            ->label('Kualitas')
                            ->options([
                                'standard' => 'Standard',
                                'premium' => 'Premium',
                            ])
                            ->required(),

                        Forms\Components\Select::make('allowed_categories')
                            ->label('Tersedia Untuk Produk')
                            ->multiple()
                            ->options([
                                'jersey' => 'Jersey',
                                'jacket' => 'Jacket',
                                'tshirt' => 'T-Shirt',
                                'kemeja' => 'Kemeja',
                            ])
                            ->required(),

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
                            ->placeholder('Meter, Kg, Roll, dll')
                            ->default('Meter')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Deskripsi & Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Foto Bahan')
                            ->image()
                            ->directory('materials')
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'standard' => 'gray',
                        'premium' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('additional_price')
                    ->label('Extra Price')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . $record->unit)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'standard' => 'Standard',
                        'premium' => 'Premium',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
