@extends('layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-check"></i> Upcoming Appointments</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">
                <i class="fas fa-calendar-alt"></i> 
                All Upcoming Appointments ({{ $upcomingAppointments->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($upcomingAppointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-clock"></i> Time</th>
                                <th><i class="fas fa-user-injured"></i> Patient</th>
                                <th><i class="fas fa-user-md"></i> Doctor</th>
                                <th><i class="fas fa-stethoscope"></i> Specializations</th>
                                <th><i class="fas fa-info-circle"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingAppointments as $appointment)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $appointment->patient->full_name }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $appointment->doctor->full_name }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $appointment->doctor->getSpecializationNamesString() }}</small>
                                    </td>
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
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('appointments.show', $appointment) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('appointments.edit', $appointment) }}" 
                                               class="btn btn-warning btn-sm" 
                                               title="Edit Appointment">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Upcoming Appointments</h4>
                    <p class="text-muted">All appointments are either completed, cancelled, or scheduled for the past.</p>
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Schedule New Appointment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
