@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0"><i class="fas fa-user-md"></i> Doctor Details</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 100px; height: 100px;">
                                    <i class="fas fa-user-md fa-3x text-white"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h4 class="text-primary">{{ $doctor->full_name }}</h4>
                                <p class="text-muted mb-0">Medical Professional</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-id-card text-primary"></i> Full Name</label>
                                <p class="form-control-plaintext">{{ $doctor->full_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-phone text-success"></i> Contact Number</label>
                                <p class="form-control-plaintext">{{ $doctor->contact }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-stethoscope text-info"></i> Specializations</label>
                        @if($doctor->specializations->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($doctor->specializations as $specialization)
                                    <span class="badge bg-primary fs-6 px-3 py-2">
                                        <i class="fas fa-medal"></i> {{ $specialization->specialization }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No specializations assigned</p>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-calendar-alt text-warning"></i> Total Appointments</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-info fs-6">{{ $doctor->appointments->count() }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-clock text-secondary"></i> Member Since</label>
                                <p class="form-control-plaintext">{{ $doctor->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($doctor->appointments->count() > 0)
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-calendar-check text-success"></i> Recent Appointments</label>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Patient</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($doctor->appointments->take(5) as $appointment)
                                            <tr>
                                                <td>{{ $appointment->patient->full_name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($appointment->status == 'Completed') bg-success
                                                        @elseif($appointment->status == 'Confirmed') bg-primary
                                                        @elseif($appointment->status == 'Cancelled') bg-danger
                                                        @else bg-warning
                                                        @endif">
                                                        {{ $appointment->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($doctor->appointments->count() > 5)
                                <p class="text-muted small">Showing 5 of {{ $doctor->appointments->count() }} appointments</p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Doctor
                        </a>
                        <a href="{{ route('doctors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('appointments.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Book Appointment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
