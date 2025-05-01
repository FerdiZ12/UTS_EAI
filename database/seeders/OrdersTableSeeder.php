<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        Order::create([
            'user_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'total_price' => 50000,
            'status' => 'completed',
        ]);

        Order::create([
            'user_id' => 2,
            'product_id' => 2,
            'quantity' => 1,
            'total_price' => 30000,
            'status' => 'pending',
        ]);
    }
}
