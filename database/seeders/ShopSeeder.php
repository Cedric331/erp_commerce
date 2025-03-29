<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shop::create([
            'enseigne' => 'Web Discovery',
            'email' => 'test@test.com',
            'slug' => 'web-discovery',
            'siret' => '123456789',
            'pays' => 'France',
        ]);
    }
}
