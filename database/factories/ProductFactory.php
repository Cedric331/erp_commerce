<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        
        return [
            'shop_id' => Shop::factory(),
            'category_id' => null,
            'brand_id' => null,
            'storage_id' => null,
            'name' => $name,
            'type' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'reference' => Str::upper(Str::random(8)),
            'barcode' => $this->faker->ean13(),
            'price_buy' => $this->faker->randomFloat(2, 1, 100),
            'price_ht' => $this->faker->randomFloat(2, 10, 200),
            'price_ttc' => $this->faker->randomFloat(2, 12, 240),
            'tva' => 20.00,
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'color' => $this->faker->colorName(),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'stock' => $this->faker->randomFloat(2, 0, 1000),
            'stock_alert' => 10,
            'unit' => $this->faker->randomElement(['unité', 'kg', 'litre']),
            'status' => 'active',
        ];
    }
}