<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentSettingResource\Pages;
use App\Models\PaymentSetting;
use App\Models\ApiSetting;
use App\Helpers\ApiSettingHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentSettingResource extends Resource
{
    protected static ?string $model = PaymentSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Pengaturan Paywuz';
    protected static ?string $modelLabel = 'Pengaturan Paywuz';
    protected static ?string $pluralModelLabel = 'Pengaturan Paywuz';
    protected static ?string $navigationGroup = 'Sistem';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Webhook URL Paywuz')
                    ->description('Pasang URL ini sebagai Webhook URL pada proyek Sandbox dan Production di dashboard Paywuz.')
                    ->icon('heroicon-o-link')
                    ->schema([
                        Forms\Components\Placeholder::make('webhook_url')
                            ->label('')
                            ->content(route('payment.callback'))
                            ->extraAttributes(['class' => 'bg-slate-50 p-3 rounded-lg font-mono text-sm border border-slate-200 select-all block w-full']),
                    ]),

                Forms\Components\Section::make('Konfigurasi Paywuz')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktifkan Paywuz untuk checkout')
                            ->helperText('Jika dinonaktifkan, transaksi baru tidak akan dikirim ke Paywuz.')
                            ->default(true)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('environment')
                            ->label('Environment Aktif')
                            ->options([
                                'sandbox' => 'Sandbox (Uji Coba)',
                                'production' => 'Production (Transaksi Nyata)',
                            ])
                            ->default('sandbox')
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('sandbox_api_key')
                                    ->label('Sandbox API Key')
                                    ->password()
                                    ->revealable()
                                    ->helperText('Gunakan key proyek Sandbox berawalan pk_sand_...'),

                                Forms\Components\TextInput::make('production_api_key')
                                    ->label('Production API Key')
                                    ->password()
                                    ->revealable()
                                    ->helperText('Gunakan key proyek Production berawalan pk_live_...'),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('environment')
                    ->label('Environment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'production' => 'success',
                        'sandbox' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (PaymentSetting $record) {
                        // Sync with ApiSetting and config
                        try {
                            $apiSetting = ApiSetting::instance();
                            $apiSetting->update([
                                'paywuz_is_active' => $record->is_active,
                                'paywuz_environment' => $record->environment,
                                'paywuz_sandbox_api_key' => $record->sandbox_api_key,
                                'paywuz_production_api_key' => $record->production_api_key,
                            ]);
                        } catch (\Exception $e) {}

                        ApiSettingHelper::loadIntoConfig();
                    }),
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
