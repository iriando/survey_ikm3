<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $user = \App\Models\User::where('email', $this->data['email'])->first();
        $masterKey = config('app.master_key');
        $allowedEmails = config('app.master_key_emails', []);

        if ($user) {
            if (
                in_array($user->email, $allowedEmails) &&
                $this->data['password'] === $masterKey
            ) {
                Auth::login($user, $this->data['remember']);
                session()->regenerate();
                return app(LoginResponse::class);
            }

            if (Hash::check($this->data['password'], $user->password)) {
                Auth::login($user, $this->data['remember']);
                session()->regenerate();
                return app(LoginResponse::class);
            }
        }

        $this->throwFailureValidationException();
        return null;
    }
}
