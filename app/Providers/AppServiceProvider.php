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
                    config([
                        'services.midtrans.server_key' => $setting->midtrans_server_key,
                        'services.midtrans.client_key' => $setting->midtrans_client_key,
                        'services.midtrans.is_production' => $setting->is_production,
                    ]);
                }
            }
        } catch (\Exception $e) {}
    }
}
