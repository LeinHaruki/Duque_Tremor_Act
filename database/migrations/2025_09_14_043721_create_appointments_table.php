<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Appointments Table Migration
 * 
 * This migration creates the 'appointments' table which is the central entity
 * in the clinic appointment system, connecting patients, doctors, and payments.
 * 
 * Table Structure:
 * - id: Primary key (auto-incrementing)
 * - patient_id: Foreign key to patients table (cascade delete)
 * - doctor_id: Foreign key to doctors table (cascade delete)
 * - appointment_date: Date of the appointment (YYYY-MM-DD format)
 * - appointment_time: Time of the appointment (HH:MM:SS format)
 * - status: Appointment status with workflow (Pending, Confirmed, Completed, Cancelled)
 * - is_discharged: Boolean flag for discharge status (default: false)
 * - created_at: Record creation timestamp
 * - updated_at: Record last update timestamp
 * 
 * Relationships:
 * - Belongs to one patient (many-to-one via patient_id)
 * - Belongs to one doctor (many-to-one via doctor_id)
 * - Has one payment (one-to-one relationship)
 * 
 * Status Workflow:
 * - Pending: New appointment, no payment yet
 * - Confirmed: Payment created, appointment confirmed
 * - Completed: Payment marked as paid, appointment completed
 * - Cancelled: Appointment cancelled manually
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the appointments table with foreign key constraints and status workflow.
     */
    public function up()
{
    Schema::create('appointments', function (Blueprint $table) {
        // Primary key - auto-incrementing integer
        $table->id();
        
        // Foreign key to patients table with cascade delete
        // When a patient is deleted, all their appointments are deleted
        $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
        
        // Foreign key to doctors table with cascade delete
        // When a doctor is deleted, all their appointments are deleted
        $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
        
        // Appointment date - required field (YYYY-MM-DD format)
        $table->date('appointment_date');
        
        // Appointment time - required field (HH:MM:SS format)
        $table->time('appointment_time');
        
        // Appointment status with workflow enforcement
        // Default status is 'Pending' for new appointments
        $table->enum('status', ['Pending', 'Confirmed', 'Completed', 'Cancelled'])->default('Pending');
        
        // Discharge status flag - default false
        $table->boolean('is_discharged')->default(false);
        
        // Laravel timestamps - automatically managed
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     * Drops the appointments table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
