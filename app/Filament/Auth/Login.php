<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getHeading(): string|Htmlable
    {
        return 'Selamat Datang';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Silakan masuk untuk mengelola inventaris.';
    }

    public function getView(): string
    {
        return 'filament.auth.login';
    }

    public function getLayout(): string
    {
        return 'filament-panels::components.layout.base';
    }
}
