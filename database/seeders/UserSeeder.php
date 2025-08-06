<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'taufik.iriando@bkn.go.id'],
            [
                'name' => 'Taufik',
                'password' => Hash::make('admin123'), // ganti dengan password aman
            ]
        );
    }
}
