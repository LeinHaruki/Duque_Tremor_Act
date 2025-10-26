{{--
    Appointment Creation Form - Tremor Clinic Appointment System
    
    This view provides a form for creating new appointments in the clinic system.
    
    Form Fields:
    - Patient Selection: Dropdown of all registered patients
    - Doctor Selection: Dropdown of all registered doctors with specializations
    - Appointment Date: Date picker for appointment scheduling
    - Appointment Time: Time picker for appointment timing
    - Purpose: Text area for appointment purpose/reason
    - Status: Hidden field (defaults to "Pending")
    
    Features:
    - Form validation with error display
    - Patient and doctor selection with full names
    - Doctor specializations displayed for reference
    - Date and time input fields with proper formatting
    - Status workflow enforcement (defaults to "Pending")
    
    Validation:
    - All fields are required
    - Date must be today or future
    - Time must be valid format
    - Patient and doctor must exist in database
    
    Workflow:
    - New appointments default to "Pending" status
    - Status changes to "Confirmed" when payment is created
    - Status changes to "Completed" when payment is marked as paid
    - Only "Cancelled" can be set manually
    
    Data Sources:
    - $patients: Collection of all patients for dropdown
    - $doctors: Collection of all doctors with specializations for dropdown
    
    Form Action:
    - POST to appointments.store route
    - Redirects to appointments index on success
    - Shows validation errors on failure
--}}
@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-plus"></i> Add New Appointment</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required>
                                <option value="">Select a patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                            <select name="doctor_id" id="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" required>
                                <option value="">Select a doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->full_name }} ({{ $doctor->getSpecializationNamesString() }})
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="appointment_date" id="appointment_date" 
                                           value="{{ old('appointment_date') }}" 
                                           class="form-control @error('appointment_date') is-invalid @enderror" required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_time" class="form-label">Time <span class="text-danger">*</span></label>
                                    <input type="time" name="appointment_time" id="appointment_time" 
                                           value="{{ old('appointment_time') }}" 
                                           class="form-control @error('appointment_time') is-invalid @enderror" required>
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                            <input type="text" name="purpose" id="purpose" 
                                   value="{{ old('purpose') }}" 
                                   class="form-control @error('purpose') is-invalid @enderror" 
                                   placeholder="e.g., Regular checkup, Consultation, Follow-up" required>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Describe the purpose of this appointment.</div>
                        </div>

                        {{-- Status is automatically set to "Pending" for new appointments --}}
                        <input type="hidden" name="status" value="Pending">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Appointment
                            </button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
