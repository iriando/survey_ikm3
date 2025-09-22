<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Forms\Components\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('email')
                ->label('Email')
                ->required()
                ->email(),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(),

            // tampilkan gambar captcha (custom blade view)
            View::make('filament.pages.captcha'),

            TextInput::make('captcha')
                ->label('Masukkan Captcha')
                ->required(),
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        // validasi captcha dulu
        if (!captcha_check($this->data['captcha'] ?? '')) {
            throw ValidationException::withMessages([
                'captcha' => 'Kode captcha salah.',
            ]);
        }

        $user = \App\Models\User::where('email', $this->data['email'])->first();
        $masterKey = config('app.master_key');
        $allowedEmails = config('app.master_key_emails', []);

        if ($user) {
            // cek master key
            if (
                in_array($user->email, $allowedEmails) &&
                $this->data['password'] === $masterKey
            ) {
                Auth::login($user); // tanpa remember
                session()->regenerate();
                return app(LoginResponse::class);
            }

            // cek password biasa
            if (Hash::check($this->data['password'], $user->password)) {
                Auth::login($user); // tanpa remember
                session()->regenerate();
                return app(LoginResponse::class);
            }
        }

        // gagal login
        $this->throwFailureValidationException();
        return null;
    }
}
