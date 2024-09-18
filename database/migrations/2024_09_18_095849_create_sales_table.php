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
        Schema::create('sales', function (Blueprint $table) {
            $table->string('id')->primary(); // Custom ID like 'sl001'
            $table->string('customer_id'); // Foreign key to customers
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->date('sale_date');
            $table->decimal('grand_total', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->enum('discount_type', ['percentage', 'amount'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('final_total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
