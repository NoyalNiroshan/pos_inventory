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
        Schema::create('supplier_return_payments', function (Blueprint $table) {
            $table->string('id')->primary(); // Custom ID like 'srp001'
            $table->string('supplier_return_id');
            $table->foreign('supplier_return_id')->references('id')->on('supplier_returns')->onDelete('cascade');
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
        Schema::dropIfExists('supplier_return_payments');
    }
};
