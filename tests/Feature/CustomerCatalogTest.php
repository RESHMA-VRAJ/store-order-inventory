<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_listing_displays_names_and_emails_without_actions(): void
    {
        Customer::factory()->create([
            'name' => 'Morgan Lee',
            'email' => 'morgan@example.com',
        ]);

        $response = $this->get(route('customers.index'));

        $response->assertOk()
            ->assertSee('Morgan Lee')
            ->assertSee('morgan@example.com')
            ->assertDontSee('Edit')
            ->assertDontSee('Delete');
    }
}
