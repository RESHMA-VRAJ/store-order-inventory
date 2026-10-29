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
        Schema::create('products', function (Blueprint $table) {
             $table->id();

            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('price', 12, 2);
            $table->decimal('tax_percentage', 5, 2)->default(0);
             $table->decimal('stock', 15, 3)->default(0)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Supports low-stock filtering
            $table->index('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
