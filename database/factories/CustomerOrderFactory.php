<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerOrderFactory extends Factory
{
    public function definition(): array
    {
         return [
            'customer_id' => Customer::factory(),
            'subtotal' => 200,
            'grand_total' => 220,
        ];
    }
}
