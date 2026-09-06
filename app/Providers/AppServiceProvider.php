<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Order::observe(\App\Observers\OrderStatusObserver::class);
        Vite::prefetch(concurrency: 3);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('payment_settings')) {
                $setting = \App\Models\PaymentSetting::first();
                if ($setting) {
                    $apiKey = $setting->environment === 'production' 
                                ? $setting->production_api_key 
                                : $setting->sandbox_api_key;
                    
                    config([
                        'services.paywuz.api_key' => $apiKey,
                        'services.paywuz.is_production' => $setting->environment === 'production',
                        'services.paywuz.is_active' => $setting->is_active,
                    ]);
                }
            }
        } catch (\Exception $e) {}
    }
}
