<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()

            // --- KONFIGURASI UI MODERN ---
            ->colors([
                'primary' => Color::Emerald, // Mengubah warna biru menjadi Emerald agar lebih modern & segar
                'danger' => Color::Rose,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'gray' => Color::Slate,
            ])
            ->font('Poppins') // Menggunakan font modern (Pastikan koneksi internet aktif untuk memuat Google Fonts)
            ->sidebarCollapsibleOnDesktop() // Sidebar bisa diciutkan agar ruang tabel lebih luas
            ->databaseNotifications() // Mengaktifkan sistem notifikasi database
            ->spa() // Single Page Application mode untuk perpindahan halaman super cepat tanpa reload full

            // --- BRANDING LAPAS JOMBANG ---
            ->brandName('SIMA Lapas Jombang')
            ->brandLogo(fn() => view('filament.admin.logo')) // Memanggil view custom logo
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/logo.png'))
            // -------------------------------------------

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])

            // --- PLUGIN KEAMANAN (SHIELD) ---
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            // -------------------------------

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
