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

            // Expanded stock types
            $table->enum('stock_type', [
                'liquid',    // For liquids (e.g., oil, water)
                'solid',     // For solids (e.g., raw materials, solid goods)
                'dress',     // For clothing or apparel items
                'powder',    // For powders (e.g., flour, chemicals)
                'gas',       // For gaseous products
                'electronics', // For electronic items (e.g., gadgets, devices)
                'medicine',  // For medical supplies or pharmaceuticals
                'furniture', // For furniture items
                'cosmetics', // For cosmetics or beauty products
                'food',      // For food products
                'beverage'   // For beverages
            ]);

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
