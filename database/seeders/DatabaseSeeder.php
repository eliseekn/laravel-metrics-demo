<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $products = Product::factory(50)->create();

        for ($i = 1; $i <= 5; $i++) {
            foreach ($products as $product) {
                Order::factory(10)->for(User::factory())->create([
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}
