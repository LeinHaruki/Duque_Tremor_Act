<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Payment Model
 * 
 * Represents a payment in the clinic appointment system.
 * Each payment is associated with an appointment and tracks payment details.
 * 
 * Database Table: payments
 * Primary Key: id
 * 
 * @property int $id
 * @property int $appointment_id Foreign key to appointments table
 * @property float $amount Payment amount
 * @property string $method Payment method (Cash, Credit Card, etc.)
 * @property string $status Payment status (Paid, Unpaid)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating or updating a payment.
     * 
     * @var array<int, string>
     */
    protected $fillable = ['appointment_id', 'amount', 'method', 'status'];

    /**
     * Relationship: Payment belongs to an appointment
     * 
     * This defines a many-to-one relationship where:
     * - Each payment belongs to exactly one appointment
     * - One appointment can have one payment
     * 
     * This relationship allows us to access appointment details from a payment
     * and vice versa.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}

