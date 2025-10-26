<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Payments Table Migration
 * 
 * This migration creates the 'payments' table which stores payment information
 * for appointments in the clinic appointment system.
 * 
 * Table Structure:
 * - id: Primary key (auto-incrementing)
 * - appointment_id: Foreign key to appointments table (cascade delete)
 * - amount: Payment amount (decimal with 10 digits total, 2 decimal places)
 * - method: Payment method (enum: Cash, Card, Online, default: Cash)
 * - status: Payment status (enum: Unpaid, Paid, Pending, default: Unpaid)
 * - created_at: Record creation timestamp
 * - updated_at: Record last update timestamp
 * 
 * Relationships:
 * - Belongs to one appointment (many-to-one via appointment_id)
 * - Connected to appointments table via appointment_id foreign key
 * 
 * Payment Workflow:
 * - Unpaid: Payment created but not yet paid (default status)
 * - Paid: Payment has been completed
 * - Pending: Payment is being processed
 * 
 * Business Rules:
 * - Each appointment can have only one payment (one-to-one relationship)
 * - Payment creation triggers appointment status change to "Confirmed"
 * - Payment marked as "Paid" triggers appointment status change to "Completed"
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the payments table with foreign key constraints and payment workflow.
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            // Primary key - auto-incrementing integer
            $table->id();
            
            // Foreign key to appointments table with cascade delete
            // When an appointment is deleted, its payment is also deleted
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            
            // Payment amount - decimal with 10 total digits, 2 decimal places
            // Allows for amounts up to 99,999,999.99
            $table->decimal('amount', 10, 2);
            
            // Payment method - restricted to specific values, default is Cash
            $table->enum('method', ['Cash', 'Card', 'Online'])->default('Cash');
            
            // Payment status with workflow - default is Unpaid
            $table->enum('status', ['Unpaid', 'Paid', 'Pending'])->default('Unpaid');
            
            // Laravel timestamps - automatically managed
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     * Drops the payments table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
