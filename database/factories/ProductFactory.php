<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'sku' => fake()->unique()->ean13(),
            'purchase_price' => fake()->randomFloat(2, 10, 50),
            'sale_price' => fake()->randomFloat(2, 60, 150),
            'stock' => fake()->numberBetween(15, 100),
        ];
    }
}