@extends('layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-credit-card"></i> Record New Payment</h2>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus"></i> Payment Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        
                        <!-- Appointment Selection -->
                        <div class="mb-3">
                            <label for="appointment_id" class="form-label">
                                <i class="fas fa-calendar-check"></i> Appointment <span class="text-danger">*</span>
                            </label>
                            <select name="appointment_id" id="appointment_id" class="form-select @error('appointment_id') is-invalid @enderror">
                                <option value="">Select an appointment...</option>
                                @foreach($appointments as $appointment)
                                    <option value="{{ $appointment->id }}" 
                                            {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                        {{ $appointment->purpose ?? 'General consultation' }} - {{ $appointment->patient->full_name }} 
                                        with Dr. {{ $appointment->doctor->full_name }}
                                        ({{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">
                                <i class="fas fa-dollar-sign"></i> Amount <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       name="amount" 
                                       id="amount" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       step="0.01" 
                                       min="0.01" 
                                       max="999999.99"
                                       value="{{ old('amount') }}"
                                       placeholder="0.00">
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label for="method" class="form-label">
                                <i class="fas fa-credit-card"></i> Payment Method <span class="text-danger">*</span>
                            </label>
                            <select name="method" id="method" class="form-select @error('method') is-invalid @enderror">
                                <option value="">Select payment method...</option>
                                <option value="Cash" {{ old('method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Card" {{ old('method') == 'Card' ? 'selected' : '' }}>Card</option>
                                <option value="Insurance" {{ old('method') == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                            </select>
                            @error('method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-info-circle"></i> Payment Status
                            </label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="Unpaid" {{ old('status', 'Unpaid') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="Paid" {{ old('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Select "Paid" to mark the appointment as completed immediately, or "Unpaid" to confirm the appointment.</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Record Payment
                            </button>
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to list
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
