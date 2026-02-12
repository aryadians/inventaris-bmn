<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Pages\Dashboard; // Import Dashboard class
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Pages\Dashboard as BaseDashboard;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->darkMode(true)
            ->login(\App\Filament\Auth\Login::class)
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Red,
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'gray' => Color::Zinc,
            ])
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->navigationGroups([
                NavigationGroup::make()
                     ->label('Transaksi')
                     ->collapsible(false),
                NavigationGroup::make()
                     ->label('Alat')
                     ->collapsible(false),
                NavigationGroup::make()
                     ->label('Administrasi')
                     ->collapsible(false),
            ])
            ->spa()
            ->renderHook(
                'panels::head.end',
                fn () => view('filament.pwa-head')
            )
            ->theme(asset('css/filament/admin/theme.css'))
            ->brandName('SIMA Lapas Jombang')
            ->brandLogo(fn() => view('filament.admin.logo'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/logo.png'))
            ->navigationItems([
                NavigationItem::make('Scan QR Code')
                    ->url(fn (): string => route('scan.index'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-qr-code')
                    ->group('Alat')
                    ->sort(10),
            ])
            // --- MENU SIDEBAR (RESOURCE) ---
            // Bagian ini WAJIB ada agar menu Aset, Room, dll muncul kembali
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
    \App\Filament\Pages\Dashboard::class, // Panggil dashboard custom kita
        ])
            ->widgets([
                // Urutan pendaftaran di sini juga menentukan kerapian
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\AssetChart::class,
                \App\Filament\Widgets\AssetConditionWidget::class,
                \App\Filament\Widgets\MaintenanceCostChart::class,
                \App\Filament\Widgets\LatestMutations::class,
                \App\Filament\Widgets\LatestPeminjaman::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->middleware([
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\Session\Middleware\AuthenticateSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\DisableBladeIconComponents::class,
                \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                \Filament\Http\Middleware\Authenticate::class,
            ]);
    }
}
