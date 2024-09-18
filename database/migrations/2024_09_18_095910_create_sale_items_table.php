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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->string('id')->primary(); // Custom ID like 'si001'
            $table->string('sale_id'); // Foreign key to sales
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->string('product_id'); // Foreign key to products
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('batch_no')->nullable();
            $table->float('quantity_sold');
            $table->enum('unit', ['kg', 'g', 'L', 'ml', 'XL', 'Large', 'Medium', 'Small']); // Unit types
            $table->decimal('price_per_unit', 10, 2);
            $table->enum('discount_type', ['percentage', 'amount'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('tax', 5, 2)->nullable();
            $table->decimal('final_total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
