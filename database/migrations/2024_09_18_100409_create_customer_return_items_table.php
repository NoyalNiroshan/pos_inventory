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
        Schema::create('customer_return_items', function (Blueprint $table) {
            $table->string('id')->primary(); // Custom ID like 'cri001'
            $table->string('customer_return_id');
            $table->foreign('customer_return_id')->references('id')->on('customer_returns')->onDelete('cascade');
            $table->string('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->float('quantity_returned');
            $table->enum('unit', ['kg', 'g', 'L', 'ml', 'piece']);
            $table->decimal('price_per_unit', 10, 2);
            $table->decimal('total_return_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_return_items');
    }
};
