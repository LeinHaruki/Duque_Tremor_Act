<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\DoctorSpecialization;

class DoctorSpecializationRequest extends FormRequest
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
        $specializationId = $this->route('specialization') ? $this->route('specialization')->id : null;
        
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'specialization' => 'required|string|max:255|min:2',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Doctor selection is required.',
            'doctor_id.exists' => 'Selected doctor does not exist.',
            'specialization.required' => 'Specialization is required.',
            'specialization.min' => 'Specialization must be at least 2 characters.',
            'specialization.max' => 'Specialization cannot exceed 255 characters.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for duplicate specialization for the same doctor
            if ($this->has(['doctor_id', 'specialization'])) {
                $query = DoctorSpecialization::where('doctor_id', $this->input('doctor_id'))
                    ->where('specialization', $this->input('specialization'));
                
                // Exclude current specialization when updating
                if ($this->route('specialization')) {
                    $query->where('id', '!=', $this->route('specialization')->id);
                }
                
                if ($query->exists()) {
                    $validator->errors()->add('specialization', 'This doctor already has this specialization.');
                }
            }
        });
    }
}
