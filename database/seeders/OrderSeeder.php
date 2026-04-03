<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        // Create some orders for each user
        foreach ($users as $user) {
            $order = Order::factory()->create(['user_id' => $user->id]);

            // Attach products to orders
            $productsToAttach = $products->random(3); // Randomly choose 3 products for this order
            foreach ($productsToAttach as $product) {
                $order->products()->attach($product, ['quantity' => rand(1, 5), 'price' => $product->price]);
            }
        }
    }
}
