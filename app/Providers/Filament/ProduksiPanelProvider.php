<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ProduksiPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('produksi')
            ->path('produksi')
            ->login()
            ->homeUrl('/')
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Halaman Utama')
                    ->url('/')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->sort(100),
            ])
            ->colors([
                'primary' => '#06402B',
            ])
            ->brandLogo(fn () => view('filament.components.logo'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.ico'))
            ->discoverResources(in: app_path('Filament/Produksi/Resources'), for: 'App\\Filament\\Produksi\\Resources')
            ->discoverPages(in: app_path('Filament/Produksi/Pages'), for: 'App\\Filament\\Produksi\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Produksi/Widgets'), for: 'App\\Filament\\Produksi\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
