<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Doctor Model
 * 
 * Represents a doctor in the clinic appointment system.
 * Stores doctor information and manages relationships with appointments and specializations.
 * 
 * Database Table: doctors
 * Primary Key: id
 * 
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_initial
 * @property string $contact
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Doctor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating or updating a doctor.
     * 
     * @var array<int, string>
     */
    protected $fillable = ['first_name', 'last_name', 'middle_initial', 'contact'];

    /**
     * Relationship: A doctor can have many appointments
     * 
     * This defines a one-to-many relationship where:
     * - One doctor can have multiple appointments
     * - Each appointment belongs to one doctor
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Relationship: A doctor can have many specializations
     * 
     * This defines a one-to-many relationship where:
     * - One doctor can have multiple specializations
     * - Each specialization belongs to one doctor
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specializations()
    {
        return $this->hasMany(DoctorSpecialization::class);
    }

    /**
     * Helper method: Get specialization names as array
     * 
     * Returns an array of specialization names for this doctor.
     * Useful for processing specializations in PHP code.
     * 
     * @return array<string> Array of specialization names
     */
    public function getSpecializationNames()
    {
        return $this->specializations->pluck('specialization')->toArray();
    }

    /**
     * Helper method: Get specialization names as comma-separated string
     * 
     * Returns a comma-separated string of specialization names.
     * Useful for displaying specializations in the UI.
     * 
     * @return string Comma-separated specialization names
     */
    public function getSpecializationNamesString()
    {
        return $this->specializations->pluck('specialization')->implode(', ');
    }

    /**
     * Accessor: Get the doctor's full name
     * 
     * This method automatically formats the doctor's name by combining
     * first name, middle initial (if present), and last name.
     * 
     * Usage: $doctor->full_name (automatically called by Laravel)
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

