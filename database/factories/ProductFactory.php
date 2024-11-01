<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'company_id' => 1,
            'name' => $this->faker->word,
            'code' => $this->faker->unique()->ean8,
            'base_price' => $this->faker->randomFloat(2, 10, 1000),
            'tax_rate' => $this->faker->randomFloat(2, 0, 0.25),
        ];
    }
}