<?php

namespace Tests\Feature;

use App\Jobs\SendOrderConfirmation;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creation_deducts_stock_and_dispatches_job(): void
    {
        Queue::fake();

        $customer = Customer::factory()->create();

        $product = Product::factory()->create([
            'price' => 100,
            'tax_percentage' => 10,
            'stock' => 10,
        ]);

        $response = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);

        $this->assertDatabaseHas('customer_orders', [
            'customer_id' => $customer->id,
            'subtotal' => 200,
            'tax' => 20,
            'grand_total' => 220,
        ]);

        Queue::assertPushed(
            SendOrderConfirmation::class
        );
    }

    public function test_insufficient_stock_does_not_create_order(): void
    {
        $customer = Customer::factory()->create();

        $product = Product::factory()->create([
            'price' => 100,
            'tax_percentage' => 10,
            'stock' => 1,
        ]);

        $response = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertUnprocessable();

        $this->assertDatabaseCount('customer_orders', 0);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 1,
        ]);
    }
}
