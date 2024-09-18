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
            $table->string('id')->primary(); // Custom ID format 'br01-ca01-sc01-pr001'
            $table->string('brand_id'); // Foreign key to brands
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->string('subcategory_id'); // Foreign key to subcategories
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->string('name');
            $table->enum('stock_type', ['liquid', 'solid', 'dress']); // Stock type enum
            $table->string('serial_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('in_stock')->default(true);
            $table->boolean('on_sale')->default(false);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
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
