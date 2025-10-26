<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
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
        $doctorId = $this->route('doctor') ? $this->route('doctor')->id : null;
        
        return [
            'first_name' => 'required|string|max:255|min:2',
            'last_name' => 'required|string|max:255|min:2',
            'middle_initial' => 'nullable|string|max:1|alpha',
            'contact' => 'required|string|max:20|unique:doctors,contact,' . $doctorId,
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'required|string|max:255|min:2',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'middle_initial.alpha' => 'Middle initial must be a letter.',
            'middle_initial.max' => 'Middle initial can only be one character.',
            'contact.required' => 'Contact number is required.',
            'contact.unique' => 'This contact number is already registered.',
            'contact.max' => 'Contact number cannot exceed 20 characters.',
            'specializations.required' => 'At least one specialization is required.',
            'specializations.min' => 'At least one specialization must be selected.',
            'specializations.*.required' => 'Each specialization is required.',
            'specializations.*.min' => 'Each specialization must be at least 2 characters.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for duplicate doctor names
            $firstName = $this->input('first_name');
            $lastName = $this->input('last_name');
            $middleInitial = $this->input('middle_initial');
            $doctorId = $this->route('doctor') ? $this->route('doctor')->id : null;
            
            if ($firstName && $lastName) {
                $query = \App\Models\Doctor::where('first_name', $firstName)
                    ->where('last_name', $lastName);
                
                // Include middle initial in the check if provided
                if ($middleInitial) {
                    $query->where('middle_initial', $middleInitial);
                } else {
                    $query->whereNull('middle_initial');
                }
                
                // Exclude current doctor when updating
                if ($doctorId) {
                    $query->where('id', '!=', $doctorId);
                }
                
                $existingDoctor = $query->first();
                
                if ($existingDoctor) {
                    $fullName = $existingDoctor->getFullNameAttribute();
                    $validator->errors()->add('first_name', "A doctor with the name '{$fullName}' already exists.");
                }
            }
            
            // Check for duplicate specializations within the same doctor
            if ($this->has('specializations')) {
                $specializations = $this->input('specializations');
                
                // Remove empty values and trim whitespace
                $specializations = array_filter(array_map('trim', $specializations));
                
                // Check for duplicates within the submitted specializations
                $duplicates = array_diff_assoc($specializations, array_unique($specializations));
                
                if (!empty($duplicates)) {
                    $validator->errors()->add('specializations', 'Duplicate specializations are not allowed.');
                }
                
                // For updates, we don't need to check for conflicts with existing specializations
                // because the user should be able to keep their existing specializations
                // The controller will handle updating the specializations properly
            }
        });
    }
}
