@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-edit"></i> Edit Appointment</h2>
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

                    <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>
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
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
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
                                           value="{{ old('appointment_date', $appointment->appointment_date) }}" 
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
                                           value="{{ old('appointment_time', $appointment->appointment_time) }}" 
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
                                   value="{{ old('purpose', $appointment->purpose) }}" 
                                   class="form-control @error('purpose') is-invalid @enderror" 
                                   placeholder="e.g., Regular checkup, Consultation, Follow-up" required>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Describe the purpose of this appointment.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <div class="d-flex align-items-center">
                                        <span class="badge 
                                            @if($appointment->status == 'Completed') bg-success
                                            @elseif($appointment->status == 'Confirmed') bg-primary
                                            @elseif($appointment->status == 'Cancelled') bg-danger
                                            @else bg-warning
                                            @endif me-3">
                                            {{ $appointment->status }}
                                        </span>
                                        @if($appointment->status !== 'Cancelled' && $appointment->status !== 'Completed')
                                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                                <option value="{{ $appointment->status }}" selected>Keep Current: {{ $appointment->status }}</option>
                                                <option value="Cancelled">Cancel Appointment</option>
                                            </select>
                                        @else
                                            <input type="hidden" name="status" value="{{ $appointment->status }}">
                                            <small class="text-muted">
                                                @if($appointment->status == 'Completed')
                                                    Status automatically set when payment was marked as paid.
                                                @else
                                                    This appointment has been cancelled.
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <strong>Status Workflow:</strong><br>
                                        • <strong>Pending</strong> → <strong>Confirmed</strong> (when payment is created)<br>
                                        • <strong>Confirmed</strong> → <strong>Completed</strong> (when payment is marked as paid)<br>
                                        • Only <strong>Cancelled</strong> can be set manually
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Update Appointment
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
