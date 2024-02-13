<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Cedric',
            'email' => 'limacedric@hotmail.fr',
            'password' => Hash::make('password')
        ]);

        User::create([
            'name' => 'DEMO',
            'email' => 'demo@demo.com',
            'password' => Hash::make('password')
        ]);
    }
}
