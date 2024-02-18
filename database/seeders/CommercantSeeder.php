<?php

namespace Database\Seeders;

use App\Models\Commercant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommercantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Commercant::create([
            'enseigne' => 'Web Discovery',
            'email' => 'test@test.com',
            'slug' => 'web-discovery',
            'siret' => '123456789',
            'pays' => 'France',
        ]);
    }
}
