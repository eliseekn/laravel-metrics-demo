<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $this->faker->randomNumber(2),
            'status' => $this->faker->randomElement(ProductStatus::values()),
            'created_at' => $this->faker->dateTimeBetween('-12 months')
        ];
    }
}
