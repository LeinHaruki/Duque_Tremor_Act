<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * DoctorSpecialization Model
 * 
 * Represents a doctor's specialization in the clinic appointment system.
 * This model allows doctors to have multiple specializations (e.g., Cardiology, Neurology).
 * 
 * Database Table: doctor_specializations
 * Primary Key: id
 * 
 * @property int $id
 * @property int $doctor_id Foreign key to doctors table
 * @property string $specialization The specialization name (e.g., "Cardiology", "Neurology")
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class DoctorSpecialization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These fields can be filled when creating or updating a doctor specialization.
     * 
     * @var array<int, string>
     */
    protected $fillable = ['doctor_id', 'specialization'];

    /**
     * Validation rules for doctor specialization
     * 
     * This method returns the validation rules that should be applied
     * when creating or updating a doctor specialization.
     * 
     * @return array<string, string> Validation rules array
     */
    public static function rules()
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'specialization' => 'required|string|max:255|min:2',
        ];
    }

    /**
     * Relationship: Doctor specialization belongs to a doctor
     * 
     * This defines a many-to-one relationship where:
     * - Many specializations can belong to one doctor
     * - Each specialization belongs to exactly one doctor
     * 
     * This allows a doctor to have multiple specializations.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
