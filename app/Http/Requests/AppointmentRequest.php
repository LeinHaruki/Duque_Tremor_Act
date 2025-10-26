<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appointment;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $appointmentId = $this->route('appointment') ? $this->route('appointment')->id : null;
        
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'purpose' => 'required|string|max:255|min:3',
            'status' => 'sometimes|string|in:Pending,Confirmed,Completed,Cancelled',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'patient_id.required' => 'Patient selection is required.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'doctor_id.required' => 'Doctor selection is required.',
            'doctor_id.exists' => 'Selected doctor does not exist.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.date' => 'Please provide a valid date.',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past.',
            'appointment_time.required' => 'Appointment time is required.',
            'appointment_time.date_format' => 'Please provide a valid time format (HH:MM).',
            'purpose.required' => 'Appointment purpose is required.',
            'purpose.min' => 'Purpose must be at least 3 characters.',
            'purpose.max' => 'Purpose cannot exceed 255 characters.',
            'status.in' => 'Status must be one of: Pending, Confirmed, Completed, Cancelled.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for duplicate appointments (same doctor, date, and time)
            if ($this->has(['doctor_id', 'appointment_date', 'appointment_time'])) {
                $query = Appointment::where('doctor_id', $this->input('doctor_id'))
                    ->where('appointment_date', $this->input('appointment_date'))
                    ->where('appointment_time', $this->input('appointment_time'));
                
                // Exclude current appointment when updating
                if ($this->route('appointment')) {
                    $query->where('id', '!=', $this->route('appointment')->id);
                }
                
                if ($query->exists()) {
                    $validator->errors()->add('appointment_time', 'This time slot is already booked for the selected doctor on this date.');
                }
            }

            // Check for duplicate appointments (same patient, date, and time)
            if ($this->has(['patient_id', 'appointment_date', 'appointment_time'])) {
                $query = Appointment::where('patient_id', $this->input('patient_id'))
                    ->where('appointment_date', $this->input('appointment_date'))
                    ->where('appointment_time', $this->input('appointment_time'));
                
                // Exclude current appointment when updating
                if ($this->route('appointment')) {
                    $query->where('id', '!=', $this->route('appointment')->id);
                }
                
                if ($query->exists()) {
                    $validator->errors()->add('appointment_time', 'This patient already has an appointment at this time on this date.');
                }
            }
        });
    }
}
