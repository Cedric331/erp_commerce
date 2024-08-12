<?php

namespace Database\Seeders;

use App\Models\StockStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockStatusSeeder extends Seeder
{

    const STATUS_VENTE = 'Vente';
    const STATUS_LIVRAISON = 'Livraison';
    const STATUS_PERTE = 'Perte';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockStatus::create([
            'name' => self::STATUS_VENTE,
            'color' =>  '#008000',
            'type' => StockStatus::TYPE_SORTIE,
            'shop_id' => 1,
        ]);

        StockStatus::create([
            'name' => self::STATUS_LIVRAISON,
            'color' => '#FFA500',
            'type' => StockStatus::TYPE_ENTREE,
            'shop_id' => 1,
        ]);

        StockStatus::create([
            'name' => self::STATUS_PERTE,
            'color' => '#FF0000',
            'type' => StockStatus::TYPE_SORTIE,
            'shop_id' => 1,
        ]);
    }
}
