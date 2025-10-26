<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Payment;

class PaymentRequest extends FormRequest
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
        $paymentId = $this->route('payment') ? $this->route('payment')->id : null;
        
        return [
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'method' => 'required|string|in:Cash,Card,Insurance',
            'status' => 'sometimes|string|in:Unpaid,Paid',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'appointment_id.required' => 'Appointment selection is required.',
            'appointment_id.exists' => 'Selected appointment does not exist.',
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Amount must be greater than 0.',
            'amount.max' => 'Amount cannot exceed 999,999.99.',
            'method.required' => 'Payment method is required.',
            'method.in' => 'Payment method must be one of: Cash, Card, Insurance.',
            'status.in' => 'Status must be one of: Unpaid, Paid.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for duplicate payment for the same appointment
            if ($this->has('appointment_id')) {
                $query = Payment::where('appointment_id', $this->input('appointment_id'));
                
                // Exclude current payment when updating
                if ($this->route('payment')) {
                    $query->where('id', '!=', $this->route('payment')->id);
                }
                
                if ($query->exists()) {
                    $validator->errors()->add('appointment_id', 'A payment already exists for this appointment.');
                }
            }
        });
    }
}
