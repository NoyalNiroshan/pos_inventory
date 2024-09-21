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
        Schema::create('suppliers', function (Blueprint $table) {
            // Custom ID like 'sup001'
            $table->string('id')->primary();

            // Supplier details
            $table->string('company_name')->nullable()->index(); // Index for fast search by company name
            $table->string('registerNum')->nullable()->unique(); // Unique registration number with index
            $table->string('email')->nullable()->index(); // Index for email to improve query performance
            $table->text('address')->nullable();

            // Multiple phone numbers stored as JSON
            $table->json('phone_numbers')->nullable();

            // Discount related fields
            $table->boolean('has_discount')->default(false);
            $table->enum('discount_type', ['percentage', 'amount'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();

            // Supplier rating (out of 5 stars)
            $table->decimal('rating', 3, 2)->nullable();

            // Delivery methods for the supplier
            $table->enum('delivery_methods', ['courier', 'own_vehicle', 'drop_shipping', 'pickup', 'postal_service'])->nullable();

            // Tax identification number for invoicing
            $table->string('tax_identification_number')->nullable();

            // Supplier type (local, international, etc.)
            $table->enum('supplier_type', ['local', 'international', 'manufacturer', 'distributor'])->default('local');

            // Banking details fields
            $table->string('account_name')->nullable();  // New field
            $table->string('account_number')->nullable(); // New field
            $table->string('bank_name')->nullable();      // New field
            $table->string('ifsc_code')->nullable();      // New field

            // Timestamps
            $table->timestamps();

            // Index for improving search on frequently queried columns
            $table->index(['company_name', 'email']); // Composite index for both name and email
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
