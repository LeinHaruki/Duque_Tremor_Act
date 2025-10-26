<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Appointment Model
 * 
 * Represents an appointment in the clinic appointment system.
 * This is the central entity that connects patients, doctors, and payments.
 * 
 * Database Table: appointments
 * Primary Key: id
 * 
 * @property int $id
 * @property int $patient_id Foreign key to patients table
 * @property int $doctor_id Foreign key to doctors table
 * @property string $appointment_date Date of the appointment (YYYY-MM-DD)
 * @property string $appointment_time Time of the appointment (HH:MM:SS)
 * @property string $purpose Purpose/reason for the appointment
 * @property string $status Appointment status (Pending, Confirmed, Completed, Cancelled)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating or updating an appointment.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id', 
        'doctor_id', 
        'appointment_date', 
        'appointment_time', 
        'purpose',
        'status'
    ];

    /**
     * Relationship: Appointment belongs to a patient
     * 
     * This defines a many-to-one relationship where:
     * - Many appointments can belong to one patient
     * - Each appointment belongs to exactly one patient
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship: Appointment belongs to a doctor
     * 
     * This defines a many-to-one relationship where:
     * - Many appointments can belong to one doctor
     * - Each appointment belongs to exactly one doctor
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Relationship: Appointment has one payment
     * 
     * This defines a one-to-one relationship where:
     * - Each appointment can have one payment
     * - Each payment belongs to one appointment
     * 
     * Note: An appointment might not have a payment yet (status: Pending)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}

