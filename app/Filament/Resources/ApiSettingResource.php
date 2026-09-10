<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApiSettingResource\Pages;
use App\Models\ApiSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApiSettingResource extends Resource
{
    protected static ?string $model = ApiSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Pengaturan API';
    protected static ?string $modelLabel = 'Pengaturan API & Integrasi';
    protected static ?string $pluralModelLabel = 'Pengaturan API & Integrasi';
    protected static ?string $navigationGroup = 'Sistem';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('API Settings')
                    ->tabs([
                        // Tab 1: Paywuz Gateway
                        Forms\Components\Tabs\Tab::make('Paywuz Gateway')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Forms\Components\Section::make('Webhook URL Callback Paywuz')
                                    ->description('Pasang URL ini sebagai Webhook URL di dashboard Paywuz.')
                                    ->icon('heroicon-o-link')
                                    ->schema([
                                        Forms\Components\Placeholder::make('paywuz_webhook_url')
                                            ->label('')
                                            ->content(route('payment.callback'))
                                            ->extraAttributes(['class' => 'bg-slate-50 p-3 rounded-lg font-mono text-sm border border-slate-200 select-all block w-full']),
                                    ]),

                                Forms\Components\Section::make('Konfigurasi Credential Paywuz')
                                    ->schema([
                                        Forms\Components\Toggle::make('paywuz_is_active')
                                            ->label('Aktifkan Paywuz Payment Gateway')
                                            ->helperText('Jika aktif, metode transaksi Paywuz akan tersedia saat checkout.')
                                            ->default(true)
                                            ->columnSpanFull(),

                                        Forms\Components\Select::make('paywuz_environment')
                                            ->label('Environment Paywuz')
                                            ->options([
                                                'sandbox' => 'Sandbox (Uji Coba)',
                                                'production' => 'Production (Live Transaksi Nyata)',
                                            ])
                                            ->default('sandbox')
                                            ->columnSpanFull(),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('paywuz_sandbox_api_key')
                                                    ->label('Sandbox API Key')
                                                    ->password()
                                                    ->revealable()
                                                    ->helperText('API Key Sandbox berawalan pk_sand_...'),

                                                Forms\Components\TextInput::make('paywuz_production_api_key')
                                                    ->label('Production API Key')
                                                    ->password()
                                                    ->revealable()
                                                    ->helperText('API Key Production berawalan pk_live_...'),
                                            ]),
                                    ]),
                            ]),

                        // Tab 2: RajaOngkir
                        Forms\Components\Tabs\Tab::make('RajaOngkir')
                            ->icon('heroicon-o-truck')
                            ->schema([
                                Forms\Components\Section::make('Konfigurasi Ongkos Kirim RajaOngkir')
                                    ->description('Kelola API Key dan lokasi kota pengiriman toko/gudang.')
                                    ->schema([
                                        Forms\Components\Toggle::make('rajaongkir_is_active')
                                            ->label('Aktifkan Kalkulator RajaOngkir')
                                            ->default(true)
                                            ->columnSpanFull(),

                                        Forms\Components\Select::make('rajaongkir_account_type')
                                            ->label('Tipe Akun RajaOngkir')
                                            ->options([
                                                'starter' => 'Starter (Gratis)',
                                                'basic' => 'Basic',
                                                'pro' => 'Pro',
                                            ])
                                            ->default('starter')
                                            ->columnSpanFull(),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('rajaongkir_api_key')
                                                    ->label('RajaOngkir API Key')
                                                    ->password()
                                                    ->revealable()
                                                    ->helperText('API Key dari dashboard rajaongkir.com'),

                                                Forms\Components\TextInput::make('rajaongkir_origin_city_id')
                                                    ->label('ID Kota Asal Pengiriman (Origin City ID)')
                                                    ->numeric()
                                                    ->default('456')
                                                    ->helperText('ID Kota asal toko/gudang (Contoh: 456 untuk Sukoharjo / Surakarta)'),
                                            ]),
                                    ]),
                            ]),

                        // Tab 3: Fonnte WhatsApp
                        Forms\Components\Tabs\Tab::make('Fonnte WhatsApp')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Forms\Components\Section::make('Konfigurasi Gateway WhatsApp (Fonnte)')
                                    ->description('Digunakan untuk notifikasi pesanan, update produksi, dan OTP WhatsApp.')
                                    ->schema([
                                        Forms\Components\Toggle::make('fonnte_is_active')
                                            ->label('Aktifkan Gateway WhatsApp Fonnte')
                                            ->default(true)
                                            ->columnSpanFull(),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('fonnte_token')
                                                    ->label('Fonnte Device Token')
                                                    ->password()
                                                    ->revealable()
                                                    ->helperText('Token device dari dashboard fonnte.com'),

                                                Forms\Components\TextInput::make('fonnte_country_code')
                                                    ->label('Default Kode Negara')
                                                    ->default('62')
                                                    ->helperText('Kode negara tanpa tanda + (Contoh: 62)'),
                                            ]),
                                    ]),
                            ]),

                        // Tab 4: FastAPI Chatbot
                        Forms\Components\Tabs\Tab::make('FastAPI Chatbot')
                            ->icon('heroicon-o-cpu-chip')
                            ->schema([
                                Forms\Components\Section::make('Konfigurasi Service NLP Chatbot')
                                    ->description('Digunakan oleh sistem AI Chatbot di aplikasi web.')
                                    ->schema([
                                        Forms\Components\Toggle::make('chatbot_is_active')
                                            ->label('Aktifkan Service FastAPI Chatbot')
                                            ->default(true)
                                            ->columnSpanFull(),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('chatbot_url')
                                                    ->label('FastAPI Chatbot Endpoint URL')
                                                    ->url()
                                                    ->default('http://127.0.0.1:8000/chatbot')
                                                    ->helperText('URL Endpoint FastAPI (Contoh: http://127.0.0.1:8000/chatbot)'),

                                                Forms\Components\TextInput::make('chatbot_timeout')
                                                    ->label('Connection Timeout (Detik)')
                                                    ->numeric()
                                                    ->default(10)
                                                    ->helperText('Batas waktu request sebelum fallback'),
                                            ]),
                                    ]),
                            ]),

                        // Tab 5: Google Gemini AI
                        Forms\Components\Tabs\Tab::make('Google Gemini AI')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Forms\Components\Section::make('Konfigurasi Google Gemini AI')
                                    ->description('Digunakan untuk kecerdasan fallback respons AI Chatbot.')
                                    ->schema([
                                        Forms\Components\Toggle::make('gemini_is_active')
                                            ->label('Aktifkan Google Gemini AI')
                                            ->default(true)
                                            ->columnSpanFull(),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('gemini_api_key')
                                                    ->label('Gemini API Key')
                                                    ->password()
                                                    ->revealable()
                                                    ->helperText('API Key dari Google AI Studio (aistudio.google.com)'),

                                                Forms\Components\Select::make('gemini_model')
                                                    ->label('Model AI Gemini')
                                                    ->options([
                                                        'gemini-1.5-flash' => 'Gemini 1.5 Flash (Sangat Cepat)',
                                                        'gemini-pro' => 'Gemini Pro (Standar)',
                                                    ])
                                                    ->default('gemini-1.5-flash'),
                                            ]),
                                    ]),
                            ]),

                        // Tab 6: Google OAuth
                        Forms\Components\Tabs\Tab::make('Google OAuth')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Forms\Components\Section::make('Konfigurasi Social Login Google')
                                    ->description('Digunakan untuk fitur Login / Daftar dengan Akun Google.')
                                    ->schema([
                                        Forms\Components\Toggle::make('google_is_active')
                                            ->label('Aktifkan Login via Akun Google')
                                            ->default(true)
                                            ->columnSpanFull(),

                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('google_client_id')
                                                    ->label('Google OAuth Client ID')
                                                    ->helperText('Client ID dari Google Cloud Console'),

                                                Forms\Components\TextInput::make('google_client_secret')
                                                    ->label('Google OAuth Client Secret')
                                                    ->password()
                                                    ->revealable()
                                                    ->helperText('Client Secret dari Google Cloud Console'),
                                            ]),

                                        Forms\Components\TextInput::make('google_redirect_uri')
                                            ->label('Google Redirect Callback URI')
                                            ->url()
                                            ->default('https://becksapparel.com/auth/google/callback')
                                            ->helperText('URL Callback yang didaftarkan pada Google Cloud Console')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('paywuz_is_active')
                    ->label('Paywuz')
                    ->boolean(),
                Tables\Columns\IconColumn::make('rajaongkir_is_active')
                    ->label('RajaOngkir')
                    ->boolean(),
                Tables\Columns\IconColumn::make('fonnte_is_active')
                    ->label('Fonnte WA')
                    ->boolean(),
                Tables\Columns\IconColumn::make('chatbot_is_active')
                    ->label('Chatbot')
                    ->boolean(),
                Tables\Columns\IconColumn::make('gemini_is_active')
                    ->label('Gemini AI')
                    ->boolean(),
                Tables\Columns\IconColumn::make('google_is_active')
                    ->label('Google OAuth')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (ApiSetting $record) {
                        try {
                            \App\Models\PaymentSetting::updateOrCreate([], [
                                'is_active' => $record->paywuz_is_active,
                                'environment' => $record->paywuz_environment,
                                'sandbox_api_key' => $record->paywuz_sandbox_api_key,
                                'production_api_key' => $record->paywuz_production_api_key,
                            ]);
                        } catch (\Exception $e) {}

                        \App\Helpers\ApiSettingHelper::loadIntoConfig();
                    }),
            ]);
    }

    public static function canCreate(): bool
    {
        return ApiSetting::count() === 0;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageApiSettings::route('/'),
        ];
    }
}
