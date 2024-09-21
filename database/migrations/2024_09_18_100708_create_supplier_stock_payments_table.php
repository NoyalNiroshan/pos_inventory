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
        Schema::create('supplier_stock_payments', function (Blueprint $table) {
            $table->string('id')->primary(); // Custom ID like 'ssp001'
            $table->string('stock_in_id'); // Foreign key to stock_in
            $table->foreign('stock_in_id')->references('id')->on('stock_in')->onDelete('cascade');
           
            $table->decimal('amount_paid', 10, 2); // Amount paid to supplier
            $table->decimal('balance_due', 10, 2)->nullable(); // Balance left to be paid
            $table->enum('payment_method', ['card', 'cash', 'bank', 'others']); // Payment method
            $table->date('payment_date'); // Payment date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_stock_payments');
    }
};
