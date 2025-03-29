<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition(): array
    {
        $enseigne = $this->faker->company();

        return [
            'enseigne' => $enseigne,
            'email' => $this->faker->unique()->safeEmail(),
            'slug' => Str::slug($enseigne),
            'siret' => $this->faker->numerify('##########'),
            'telephone' => $this->faker->phoneNumber(),
            'adresse' => $this->faker->streetAddress(),
            'adresse_2' => $this->faker->streetAddress(),
            'ville' => $this->faker->city(),
            'code_postal' => $this->faker->postcode(),
            'trial_ends_at' => now()->addDays(7),
            'stripe_id' => 'cus_' . Str::random(14),
            'pays' => 'France',
        ];
    }
}
