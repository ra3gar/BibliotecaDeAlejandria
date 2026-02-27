<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name'  => 'Sistema',
            'email'      => 'admin@biblioteca.com',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
        ]);

        User::create([
            'first_name' => 'Juan',
            'last_name'  => 'Pérez',
            'email'      => 'juan@biblioteca.com',
            'password'   => Hash::make('password'),
            'role'       => 'user',
        ]);
    }
}
