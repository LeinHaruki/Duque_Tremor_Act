<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Patient Model
 * 
 * Represents a patient in the clinic appointment system.
 * Stores patient information and manages relationships with appointments.
 * 
 * Database Table: patients
 * Primary Key: id
 * 
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_initial
 * @property int $age
 * @property string $gender
 * @property string $contact
 * @property string $address
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating or updating a patient.
     * 
     * @var array<int, string>
     */
    protected $fillable = ['first_name', 'last_name', 'middle_initial', 'age', 'gender', 'contact', 'address'];

    /**
     * Relationship: A patient can have many appointments
     * 
     * This defines a one-to-many relationship where:
     * - One patient can have multiple appointments
     * - Each appointment belongs to one patient
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Accessor: Get the patient's full name
     * 
     * This method automatically formats the patient's name by combining
     * first name, middle initial (if present), and last name.
     * 
     * Usage: $patient->full_name (automatically called by Laravel)
     * 
     * @return string The formatted full name
     */
    public function getFullNameAttribute()
    {
        $name = $this->first_name . ' ' . $this->last_name;
        if ($this->middle_initial) {
            $name = $this->first_name . ' ' . $this->middle_initial . '. ' . $this->last_name;
        }
        return $name;
    }
}

