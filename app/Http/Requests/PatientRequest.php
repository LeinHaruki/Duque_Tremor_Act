<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
        $patientId = $this->route('patient') ? $this->route('patient')->id : null;
        
        return [
            'first_name' => 'required|string|max:255|min:2',
            'last_name' => 'required|string|max:255|min:2',
            'middle_initial' => 'nullable|string|max:1|alpha',
            'age' => 'required|integer|min:0|max:150',
            'gender' => 'required|string|in:Male,Female,Other',
            'contact' => 'required|string|max:20|unique:patients,contact,' . $patientId,
            'address' => 'required|string|max:500|min:10',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $patientId = $this->route('patient') ? $this->route('patient')->id : null;
            $firstName = $this->input('first_name');
            $lastName = $this->input('last_name');
            $middleInitial = $this->input('middle_initial');

            // Check for duplicate patient based on name combination
            $query = \App\Models\Patient::where('first_name', $firstName)
                ->where('last_name', $lastName);

            // Include middle initial in the check if provided
            if ($middleInitial) {
                $query->where('middle_initial', $middleInitial);
            } else {
                $query->whereNull('middle_initial');
            }

            // Exclude current patient if editing
            if ($patientId) {
                $query->where('id', '!=', $patientId);
            }

            if ($query->exists()) {
                $name = $firstName . ' ' . ($middleInitial ? $middleInitial . '. ' : '') . $lastName;
                $validator->errors()->add('first_name', "A patient with the name '{$name}' already exists in the system.");
            }
        });
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
            'age.required' => 'Patient age is required.',
            'age.integer' => 'Age must be a valid number.',
            'age.min' => 'Age cannot be negative.',
            'age.max' => 'Age cannot exceed 150 years.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be Male, Female, or Other.',
            'contact.required' => 'Contact number is required.',
            'contact.unique' => 'This contact number is already registered.',
            'contact.max' => 'Contact number cannot exceed 20 characters.',
            'address.required' => 'Address is required.',
            'address.min' => 'Address must be at least 10 characters.',
            'address.max' => 'Address cannot exceed 500 characters.',
        ];
    }
}
