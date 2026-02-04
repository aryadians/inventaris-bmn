<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->autocomplete()
                    ->autofocus()
                    ->placeholder('nama@lapas.go.id')
                    ->extraInputAttributes(['tabindex' => 1]),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->placeholder('Masukkan password Anda')
                    ->extraInputAttributes(['tabindex' => 2]),
                Checkbox::make('remember')
                    ->label('Ingat Saya')
                    ->extraAttributes(['tabindex' => 3]),
            ])
            ->statePath('data');
    }

    public function getHeading(): string|Htmlable
    {
        return 'Selamat Datang di SIMA';
    }

    public function getSubHeading(): string|Htmlable|null
    {
        return 'Sistem Informasi Manajemen Aset - Lapas Jombang';
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
