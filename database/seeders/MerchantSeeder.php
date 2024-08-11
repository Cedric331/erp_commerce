<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Merchant::create([
            'enseigne' => 'Web Discovery',
            'email' => 'test@test.com',
            'slug' => 'web-discovery',
            'siret' => '123456789',
            'pays' => 'France',
        ]);
    }
}
