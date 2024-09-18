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
        Schema::create('customer_return_payments', function (Blueprint $table) {
            $table->string('id')->primary(); // Custom ID like 'crp001'
            $table->string('customer_return_id');
            $table->foreign('customer_return_id')->references('id')->on('customer_returns')->onDelete('cascade');
            $table->decimal('amount_refunded', 10, 2);
            $table->enum('payment_method', ['card', 'cash', 'bank', 'others']);
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_return_payments');
    }
};
