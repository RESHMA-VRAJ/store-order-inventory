<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_order_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_order_id')
                ->constrained('customer_orders')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');

            // Price and tax captured at the time of ordering
            $table->decimal('unit_price', 12, 2);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('line_total', 12, 2);

            $table->timestamps();

            // Useful for fetching all items in an order
            $table->index(
                ['customer_order_id', 'product_id'],
                'order_items_order_product_index'
            );

            // Useful for looking up orders containing a product
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_order_details');
    }
};
