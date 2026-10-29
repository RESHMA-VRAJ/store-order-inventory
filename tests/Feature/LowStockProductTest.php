<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LowStockProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_products_below_the_stock_threshold(): void
    {
        $lowStockProduct = Product::factory()->create([
            'name' => 'Low Stock Product',
            'stock' => 5,
        ]);

        Product::factory()->create([
            'name' => 'Threshold Product',
            'stock' => 10,
        ]);

        Product::factory()->create([
            'name' => 'Available Product',
            'stock' => 20,
        ]);

        $response = $this->getJson(
            '/api/products/low-stock?threshold=10'
        );

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment([
                'id' => $lowStockProduct->id,
                'name' => 'Low Stock Product',
                'stock' => 5,
            ])
            ->assertJsonMissing([
                'name' => 'Threshold Product',
                'stock' => 10,
            ])
            ->assertJsonMissing([
                'name' => 'Available Product',
                'stock' => 20,
            ]);
    }

    public function test_it_uses_five_as_the_default_threshold(): void
    {
        Product::factory()->create([
            'name' => 'Low Stock Product',
            'stock' => 4,
        ]);

        Product::factory()->create([
            'name' => 'Threshold Product',
            'stock' => 5,
        ]);

        Product::factory()->create([
            'name' => 'Available Product',
            'stock' => 10,
        ]);

        $response = $this->getJson('/api/products/low-stock');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment([
                'name' => 'Low Stock Product',
                'stock' => 4,
            ])
            ->assertJsonMissing([
                'name' => 'Threshold Product',
                'stock' => 5,
            ])
            ->assertJsonMissing([
                'name' => 'Available Product',
                'stock' => 10,
            ]);
    }

    public function test_threshold_must_be_a_non_negative_integer(): void
    {
        $response = $this->getJson(
            '/api/products/low-stock?threshold=-1'
        );

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('threshold');
    }
}
