<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'status' => $this->faker->randomElement(OrderStatus::values()),
            'created_at' => $this->faker->dateTimeBetween('-12 months')
        ];
    }
}
