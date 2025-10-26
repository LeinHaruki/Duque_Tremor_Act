<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Patients Table Migration
 * 
 * This migration creates the 'patients' table which stores patient information
 * for the clinic appointment system.
 * 
 * Table Structure:
 * - id: Primary key (auto-incrementing)
 * - name: Patient's full name (string)
 * - age: Patient's age (integer, nullable)
 * - gender: Patient's gender (enum: Male, Female, Other, nullable)
 * - contact: Patient's contact information (string, nullable)
 * - address: Patient's address (string, nullable)
 * - created_at: Record creation timestamp
 * - updated_at: Record last update timestamp
 * 
 * Relationships:
 * - One patient can have many appointments (one-to-many)
 * - Connected to appointments table via patient_id foreign key
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the patients table with all necessary columns and constraints.
     */
    public function up()
{
    Schema::create('patients', function (Blueprint $table) {
        // Primary key - auto-incrementing integer
        $table->id();
        
        // Patient's full name - required field
        $table->string('name');
        
        // Patient's age - optional field
        $table->integer('age')->nullable();
        
        // Patient's gender - restricted to specific values, optional
        $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
        
        // Patient's contact information - optional field
        $table->string('contact')->nullable();
        
        // Patient's address - optional field
        $table->string('address')->nullable();
        
        // Laravel timestamps - automatically managed
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     * Drops the patients table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
