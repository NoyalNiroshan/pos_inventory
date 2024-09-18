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
    { Schema::create('sales_payments', function (Blueprint $table) {
        $table->string('id')->primary(); // Custom ID like 'sp001'
        $table->string('sale_id');
        $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        $table->decimal('amount_paid', 10, 2);
        $table->enum('payment_method', ['card', 'cash', 'bank', 'others']);
        $table->date('payment_date');
        $table->decimal('balance_due', 10, 2)->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_payments');
    }
};
