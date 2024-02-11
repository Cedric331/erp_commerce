<?php

namespace Database\Seeders;

use App\Models\StockStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockStatusSeeder extends Seeder
{

    const STATUS_PROGRESS = 'En cours';
    const STATUS_COMPLETED = 'Traité';
    const STATUS_ERROR = 'Erreur';
    const STATUS_CANCELLED = 'Annulé';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockStatus::create([
            'name' => self::STATUS_PROGRESS,
            'color' => '#FFA500',
            'commercant_id' => 1,
        ]);

        StockStatus::create([
            'name' => self::STATUS_COMPLETED,
            'color' => '#008000',
            'commercant_id' => 1,
        ]);

        StockStatus::create([
            'name' => self::STATUS_ERROR,
            'color' => '#FF0000',
            'commercant_id' => 1,
        ]);

        StockStatus::create([
            'name' => self::STATUS_CANCELLED,
            'color' => '#000000',
            'commercant_id' => 1,
        ]);
    }
}
