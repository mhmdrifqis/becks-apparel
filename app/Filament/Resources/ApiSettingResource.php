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
                Forms\Components\Grid::make(2)
                    ->schema([
                        // Card 1: Paywuz Gateway
                        Forms\Components\Section::make('API 1: 💳 Paywuz Payment Gateway')
                            ->description('Integrasi Payment Gateway Paywuz untuk checkout transaksi.')
                            ->icon('heroicon-o-credit-card')
                            ->collapsible()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Toggle::make('paywuz_is_active')
                                    ->label('Aktifkan Paywuz Payment Gateway')
                                    ->default(true),

                                Forms\Components\Select::make('paywuz_environment')
                                    ->label('Environment Status')
                                    ->options([
                                        'sandbox' => 'Sandbox (Uji Coba)',
                                        'production' => 'Production (Transaksi Live)',
                                    ])
                                    ->default('sandbox'),

                                Forms\Components\TextInput::make('paywuz_sandbox_api_key')
                                    ->label('Sandbox API Key')
                                    ->password()
                                    ->revealable()
                                    ->placeholder('pk_sand_...')
                                    ->helperText('Klik ikon mata 👁️ untuk melihat atau menyembunyikan API Key'),

                                Forms\Components\TextInput::make('paywuz_production_api_key')
                                    ->label('Production API Key')
                                    ->password()
                                    ->revealable()
                                    ->placeholder('pk_live_...')
                                    ->helperText('Klik ikon mata 👁️ untuk melihat atau menyembunyikan API Key'),

                                Forms\Components\Placeholder::make('paywuz_webhook_url')
                                    ->label('Webhook URL Callback Paywuz')
                                    ->content(fn () => url('/payment/callback'))
                                    ->extraAttributes(['class' => 'bg-slate-50 p-2.5 rounded-lg font-mono text-xs border border-slate-200 select-all block w-full']),
                            ]),

                        // Card 2: RajaOngkir Shipping
                        Forms\Components\Section::make('API 2: 🚚 RajaOngkir Shipping API')
                            ->description('Kalkulator perhitungan ongkos kirim ekspedisi Indonesia.')
                            ->icon('heroicon-o-truck')
                            ->collapsible()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Toggle::make('rajaongkir_is_active')
                                    ->label('Aktifkan Kalkulator RajaOngkir')
                                    ->default(true),

                                Forms\Components\Select::make('rajaongkir_account_type')
                                    ->label('Tipe Akun RajaOngkir')
                                    ->options([
                                        'starter' => 'Starter (Gratis)',
                                        'basic' => 'Basic',
                                        'pro' => 'Pro',
                                    ])
                                    ->default('starter'),

                                Forms\Components\TextInput::make('rajaongkir_api_key')
                                    ->label('RajaOngkir API Key')
                                    ->password()
                                    ->revealable()
                                    ->helperText('Klik ikon mata 👁️ untuk melihat atau menyembunyikan API Key'),

                                Forms\Components\TextInput::make('rajaongkir_origin_city_id')
                                    ->label('ID Kota Asal Toko/Gudang')
                                    ->numeric()
                                    ->default('456')
                                    ->helperText('ID Kota asal pengiriman toko (Contoh: 456)'),
                            ]),

                        // Card 3: Fonnte WhatsApp Gateway
                        Forms\Components\Section::make('API 3: 💬 Fonnte WhatsApp Gateway')
                            ->description('Pengiriman notifikasi status pesanan, produksi & WA OTP.')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->collapsible()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Toggle::make('fonnte_is_active')
                                    ->label('Aktifkan Gateway WhatsApp Fonnte')
                                    ->default(true),

                                Forms\Components\TextInput::make('fonnte_token')
                                    ->label('Fonnte Device Token')
                                    ->password()
                                    ->revealable()
                                    ->helperText('Klik ikon mata 👁️ untuk melihat atau menyembunyikan Device Token'),

                                Forms\Components\TextInput::make('fonnte_country_code')
                                    ->label('Default Kode Negara')
                                    ->default('62')
                                    ->helperText('Kode negara pengiriman tanpa tanda + (Contoh: 62)'),
                            ]),

                        // Card 4: FastAPI Chatbot Integration
                        Forms\Components\Section::make('API 4: 🤖 FastAPI NLP Chatbot')
                            ->description('Integrasi service backend kecerdasan buatan NLP Chatbot.')
                            ->icon('heroicon-o-cpu-chip')
                            ->collapsible()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Toggle::make('chatbot_is_active')
                                    ->label('Aktifkan Service FastAPI Chatbot')
                                    ->default(true),

                                Forms\Components\TextInput::make('chatbot_url')
                                    ->label('FastAPI Chatbot Endpoint URL')
                                    ->url()
                                    ->default('http://127.0.0.1:8000/chatbot')
                                    ->helperText('URL API FastAPI NLP Chatbot'),

                                Forms\Components\TextInput::make('chatbot_timeout')
                                    ->label('Connection Timeout (Detik)')
                                    ->numeric()
                                    ->default(10)
                                    ->helperText('Batas waktu respons sebelum fallback'),
                            ]),

                        // Card 5: Google Gemini AI
                        Forms\Components\Section::make('API 5: ✨ Google Gemini AI API')
                            ->description('Kecerdasan AI untuk fallback respons pertanyaan umum pelanggan.')
                            ->icon('heroicon-o-sparkles')
                            ->collapsible()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Toggle::make('gemini_is_active')
                                    ->label('Aktifkan Google Gemini AI')
                                    ->default(true),

                                Forms\Components\TextInput::make('gemini_api_key')
                                    ->label('Gemini API Key')
                                    ->password()
                                    ->revealable()
                                    ->helperText('Klik ikon mata 👁️ untuk melihat atau menyembunyikan API Key'),

                                Forms\Components\Select::make('gemini_model')
                                    ->label('Model AI Gemini')
                                    ->options([
                                        'gemini-1.5-flash' => 'Gemini 1.5 Flash (Sangat Cepat)',
                                        'gemini-pro' => 'Gemini Pro (Standar)',
                                    ])
                                    ->default('gemini-1.5-flash'),
                            ]),

                        // Card 6: Google OAuth Socialite
                        Forms\Components\Section::make('API 6: 🔑 Google OAuth Social Login')
                            ->description('Otentikasi Login & Registrasi cepat via Akun Google.')
                            ->icon('heroicon-o-user-group')
                            ->collapsible()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Toggle::make('google_is_active')
                                    ->label('Aktifkan Login via Google')
                                    ->default(true),

                                Forms\Components\TextInput::make('google_client_id')
                                    ->label('Google Client ID')
                                    ->helperText('OAuth Client ID dari Google Cloud Console'),

                                Forms\Components\TextInput::make('google_client_secret')
                                    ->label('Google Client Secret')
                                    ->password()
                                    ->revealable()
                                    ->helperText('Klik ikon mata 👁️ untuk melihat atau menyembunyikan Client Secret'),

                                Forms\Components\TextInput::make('google_redirect_uri')
                                    ->label('Redirect Callback URI')
                                    ->url()
                                    ->default('https://becksapparel.com/auth/google/callback'),
                            ]),

                        // Card 7: Biteship Logistics API
                        Forms\Components\Section::make('API 7: 📦 Biteship Logistics & Tracking')
                            ->description('Integrasi kurir ekspedisi lanjutan & lacak resi otomatis.')
                            ->icon('heroicon-o-archive-box')
                            ->collapsible()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Toggle::make('biteship_is_active')
                                    ->label('Aktifkan Biteship Logistics')
                                    ->default(false),

                                Forms\Components\TextInput::make('biteship_api_key')
                                    ->label('Biteship API Key')
                                    ->password()
                                    ->revealable()
                                    ->helperText('Klik ikon mata 👁️ untuk melihat atau menyembunyikan API Key'),

                                Forms\Components\TextInput::make('biteship_origin_postal_code')
                                    ->label('Kode Pos Asal Pengiriman')
                                    ->numeric()
                                    ->placeholder('Contoh: 57123'),
                            ]),

                        // Card 8: SMTP Mail Gateway
                        Forms\Components\Section::make('API 8: 📧 SMTP Mail Gateway Service')
                            ->description('Layanan pengiriman email sistem (Password Reset & Notifikasi).')
                            ->icon('heroicon-o-envelope')
                            ->collapsible()
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Toggle::make('smtp_is_active')
                                    ->label('Aktifkan Layanan Email SMTP')
                                    ->default(true),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('smtp_host')
                                            ->label('SMTP Host')
                                            ->default('mail.becksapparel.com'),

                                        Forms\Components\TextInput::make('smtp_port')
                                            ->label('SMTP Port')
                                            ->numeric()
                                            ->default(465),
                                    ]),

                                Forms\Components\TextInput::make('smtp_username')
                                    ->label('SMTP Username'),

                                Forms\Components\TextInput::make('smtp_password')
                                    ->label('SMTP Password')
                                    ->password()
                                    ->revealable()
                                    ->helperText('Klik ikon mata 👁️ untuk melihat atau menyembunyikan Password'),

                                Forms\Components\Select::make('smtp_encryption')
                                    ->label('Tipe Enkripsi')
                                    ->options([
                                        'ssl' => 'SSL (Port 465)',
                                        'tls' => 'TLS (Port 587)',
                                        'none' => 'Tanpa Enkripsi',
                                    ])
                                    ->default('ssl'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $hasBiteship = \Illuminate\Support\Facades\Schema::hasColumn('api_settings', 'biteship_is_active');
        $hasSmtp = \Illuminate\Support\Facades\Schema::hasColumn('api_settings', 'smtp_is_active');

        $columns = [
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
        ];

        if ($hasBiteship) {
            $columns[] = Tables\Columns\IconColumn::make('biteship_is_active')
                ->label('Biteship')
                ->boolean();
        }

        if ($hasSmtp) {
            $columns[] = Tables\Columns\IconColumn::make('smtp_is_active')
                ->label('SMTP Email')
                ->boolean();
        }

        $columns[] = Tables\Columns\TextColumn::make('updated_at')
            ->label('Terakhir Diperbarui')
            ->dateTime('d M Y H:i')
            ->sortable();

        return $table
            ->columns($columns)
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

    public static function canViewAny(): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasTable('api_settings');
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function canCreate(): bool
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('api_settings')) {
                return false;
            }
            return ApiSetting::count() === 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageApiSettings::route('/'),
        ];
    }
}
