<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $user = User::firstOrCreate(
            ['email' => 'taufik.iriando@bkn.go.id'],
            [
                'name' => 'Taufik',
                'password' => Hash::make('admin123'),
            ]
        );

        if (! $user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }
    }
}
