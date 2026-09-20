<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    
    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Katalog Paket';

    protected static ?string $modelLabel = 'Paket';

    protected static ?string $pluralModelLabel = 'Paket';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Paket')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Paket')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(Package::class, 'slug', ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'jersey' => 'Jersey',
                                'jacket' => 'Jacket',
                                'tshirt' => 'T-Shirt',
                                'kemeja' => 'Kemeja',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('base_price')
                            ->label('Harga Dasar')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\TextInput::make('weight')
                            ->label('Berat Produk')
                            ->numeric()
                            ->suffix('gram')
                            ->default(250)
                            ->required(),

                        Forms\Components\FileUpload::make('images')
                            ->label('Gambar Produk')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->directory('packages')
                            ->columnSpanFull()
                            ->disk('public')
                            ->hiddenOn('view'),

                        Forms\Components\Placeholder::make('images_view')
                            ->label('Gambar Produk')
                            ->hiddenOn(['create', 'edit'])
                            ->content(function ($record) {
                                $files = array_values(array_filter($record?->images ?? []));
                                if ($files === []) return new \Illuminate\Support\HtmlString('<span style="font-size:14px;color:#6b7280">Tidak ada gambar produk.</span>');
                                
                                $html = '<div style="display:flex;flex-wrap:wrap;gap:12px">';
                                foreach ($files as $index => $file) {
                                    $url = e(\Illuminate\Support\Facades\Storage::disk('public')->url($file));
                                    $html .= '<div style="width:124px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;padding:8px">
                                        <a href="' . $url . '" target="_blank" rel="noopener" title="Buka ukuran asli">
                                            <img src="' . $url . '" alt="Gambar ' . ($index + 1) . '" style="display:block;width:100px;height:100px;object-fit:cover;border-radius:8px">
                                        </a>
                                        <a href="' . $url . '" download style="display:block;text-align:center;margin-top:6px;font-size:10px;font-weight:600;color:#4b5563">Unduh</a>
                                    </div>';
                                }
                                $html .= '</div>';
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Deskripsi & Spek')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->rows(3),

                        Forms\Components\RichEditor::make('specification')
                            ->label('Spesifikasi Lengkap')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                            ->label('Foto')
                            ->stacked()
                            ->limit(3),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'jersey' => 'Jersey',
                        'jacket' => 'Jacket',
                        'tshirt' => 'T-Shirt',
                        'kemeja' => 'Kemeja',
                    ]),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
        ];
    }
}
