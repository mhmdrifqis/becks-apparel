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
                            
                        Forms\Components\CheckboxList::make('product_types')
                            ->label('Berlaku untuk Produk')
                            ->options([
                                'jersey'  => 'Jersey',
                                'jacket'  => 'Jaket',
                                'tshirt'  => 'Kaos (T-Shirt)',
                                'kemeja'  => 'Kemeja',
                            ])
                            ->helperText('Kosongkan = berlaku untuk semua produk')
                            ->columns(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Deskripsi & Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Foto Bahan')
                            ->image()
                            ->directory('materials')
                            ->columnSpanFull()
                            ->hiddenOn('view'),

                        Forms\Components\Placeholder::make('image_path_view')
                            ->label('Foto Bahan')
                            ->hiddenOn(['create', 'edit'])
                            ->content(function ($record) {
                                $file = $record?->image_path;
                                if (!$file) return new \Illuminate\Support\HtmlString('<span style="font-size:14px;color:#6b7280">Tidak ada foto bahan.</span>');
                                
                                $url = e(\Illuminate\Support\Facades\Storage::disk('public')->url($file));
                                return new \Illuminate\Support\HtmlString('
                                <div style="display:inline-block;width:124px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;padding:8px">
                                    <a href="' . $url . '" target="_blank" rel="noopener" title="Buka ukuran asli">
                                        <img src="' . $url . '" alt="Foto Bahan" style="display:block;width:100px;height:100px;object-fit:cover;border-radius:8px">
                                    </a>
                                    <a href="' . $url . '" download style="display:block;text-align:center;margin-top:6px;font-size:10px;font-weight:600;color:#4b5563">Unduh</a>
                                </div>');
                            })
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
                    
                Tables\Columns\TextColumn::make('product_types')
                    ->label('Tipe Produk')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $types = $record->product_types;
                        return (empty($types) || !is_array($types)) ? ['Semua'] : $types;
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('additional_price')
                    ->label('Harga Tambahan')
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
                Tables\Filters\SelectFilter::make('product_types')
                    ->label('Jenis Produk')
                    ->options([
                        'jersey' => 'Jersey',
                        'jacket' => 'Jaket',
                        'tshirt' => 'Kaos',
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
