<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_displays_products_and_their_stock_without_actions(): void
    {
        Product::factory()->create([
            'name' => 'Green Tea',
            'code' => 'TEA-001',
            'stock' => 7.5,
        ]);

        Product::factory()->create([
            'name' => 'Black Tea',
            'code' => 'TEA-002',
            'stock' => 0,
        ]);

        $response = $this->get(route('products.catalog'));

        $response->assertOk()
            ->assertSee('Black Tea')
            ->assertSee('TEA-001')
            ->assertSee('7.5')
            ->assertDontSee('Add Product')
            ->assertDontSee('Edit');
    }
}
