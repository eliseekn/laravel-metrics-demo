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
     *
     * @return void
     */
    public function run()
    {
        $products = Product::factory(10)->create();

        for ($i = 1; $i <=5; $i++) {
            foreach ($products as $product) {
                Order::factory(5)->for(User::factory())->create([
                    'product_id' => $product->id
                ]);
            }
        }
    }
}
