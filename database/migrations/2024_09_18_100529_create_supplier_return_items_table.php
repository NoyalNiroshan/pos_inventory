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
        Schema::create('supplier_return_items', function (Blueprint $table) {
            $table->string('id')->primary(); // Custom ID like 'sri001'
            $table->string('supplier_return_id');
            $table->foreign('supplier_return_id')->references('id')->on('supplier_returns')->onDelete('cascade');
            $table->string('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('batch_no')->nullable();
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
        Schema::dropIfExists('supplier_return_items');
    }
};
