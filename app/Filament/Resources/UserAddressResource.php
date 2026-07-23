<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserAddressResource\Pages;
use App\Models\UserAddress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserAddressResource extends Resource
{
    protected static ?string $model = UserAddress::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Alamat User';

    protected static ?string $modelLabel = 'Alamat User';

    protected static ?string $pluralModelLabel = 'Alamat User';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Alamat')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pemilik Alamat (User)')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('label')
                            ->label('Label Alamat')
                            ->placeholder('Contoh: Rumah, Kantor')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('nama_penerima')
                            ->label('Nama Penerima')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('no_telepon')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(30),

                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('provinsi')
                            ->label('Provinsi')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('kota')
                            ->label('Kota / Kabupaten')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('kode_pos')
                            ->label('Kode Pos')
                            ->required()
                            ->maxLength(20),

                        Forms\Components\Toggle::make('is_default')
                            ->label('Jadikan Alamat Utama (Default)')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('nama_penerima')
                    ->label('Nama Penerima')
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('No. Telepon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kota')
                    ->label('Kota')
                    ->searchable(),

                Tables\Columns\TextColumn::make('provinsi')
                    ->label('Provinsi')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserAddresses::route('/'),
            'create' => Pages\CreateUserAddress::route('/create'),
            'edit' => Pages\EditUserAddress::route('/{record}/edit'),
        ];
    }
}
