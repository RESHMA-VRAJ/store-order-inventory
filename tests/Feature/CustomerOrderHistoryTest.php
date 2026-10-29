<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderDetail;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_order_history_using_email(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
        ]);

        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 100,
            'tax_percentage' => 10,
            'stock' => 10,
        ]);

        $order = CustomerOrder::factory()->create([
            'customer_id' => $customer->id,
        ]);

        CustomerOrderDetail::factory()->create([
             'customer_order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 100,
            'tax_percentage' => 10,
            'line_total' => 220,
        ]);

        $response = $this->getJson(
            '/api/customers/orders?email=customer@example.com'
        );

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.customer_id', $customer->id)
            ->assertJsonPath(
                'data.0.customer_order_details.0.product.id',
                $product->id
            );
    }

   public function test_order_history_returns_404_for_unknown_customer(): void
{
    $response = $this->getJson(
        '/api/customers/orders?email=unknown@example.com'
    );

    $response->assertNotFound();
}

    public function test_order_history_requires_a_valid_email(): void
    {
        $response = $this->getJson(
            '/api/customers/orders?email=invalid-email'
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }
}
