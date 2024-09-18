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
        Schema::create('stock_in_items', function (Blueprint $table) {
            $table->string('id')->primary(); // Custom ID like 'sti001'
            $table->string('stock_in_id');
            $table->foreign('stock_in_id')->references('id')->on('stock_in')->onDelete('cascade');
            $table->string('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->float('quantity');
            $table->enum('unit', ['kg', 'g', 'L', 'ml', 'XL', 'Large', 'Medium', 'Small']); // Unit types
            $table->decimal('price_per_unit', 10, 2);
            $table->date('expiration_date')->nullable();
            $table->float('discount')->nullable();
            $table->date('manufacturing_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_in_items');
    }
};
