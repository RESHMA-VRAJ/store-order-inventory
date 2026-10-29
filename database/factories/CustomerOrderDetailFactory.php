<?php

namespace Database\Factories;

use App\Models\CustomerOrder;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerOrderDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_order_id' => CustomerOrder::factory(),
            'product_id' => Product::factory(),
            'quantity' => 2,
            'unit_price' => 100,
            'tax_percentage' => 10,
            'line_total' => 220,
        ];
    }
}