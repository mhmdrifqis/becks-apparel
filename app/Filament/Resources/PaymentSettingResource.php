<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentSettingResource\Pages;
use App\Filament\Resources\PaymentSettingResource\RelationManagers;
use App\Models\PaymentSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentSettingResource extends Resource
{
    protected static ?string $model = PaymentSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Midtrans Setting';
    protected static ?string $modelLabel = 'Midtrans Setting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kredensial Midtrans')->schema([
                    Forms\Components\TextInput::make('midtrans_server_key')
                        ->label('Server Key')
                        ->password()
                        ->revealable()
                        ->required(),
                    Forms\Components\TextInput::make('midtrans_client_key')
                        ->label('Client Key')
                        ->required(),
                    Forms\Components\Toggle::make('is_production')
                        ->label('Mode Production (Live)')
                        ->helperText('Aktifkan untuk Live/Production. Nonaktifkan untuk Testing/Sandbox.')
                        ->default(false),
                ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('midtrans_client_key')
                    ->label('Client Key')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_production')
                    ->label('Production Mode')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function canCreate(): bool
    {
        return PaymentSetting::count() === 0;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePaymentSettings::route('/'),
        ];
    }
}
