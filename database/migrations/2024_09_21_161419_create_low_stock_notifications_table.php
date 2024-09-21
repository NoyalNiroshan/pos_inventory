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
    { Schema::create('low_stock_notifications', function (Blueprint $table) {
        $table->id();
        $table->string('product_id'); // Foreign key to products
        $table->integer('current_stock'); // Stock level when alert was triggered
        $table->timestamp('notified_at')->useCurrent(); // When the notification was created
        $table->boolean('resolved')->default(false); // Whether the issue was resolved
        $table->timestamps();

        // Foreign key constraint
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('low_stock_notifications');
    }
};
