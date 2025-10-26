@extends('layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-check"></i> Appointments Management</h2>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Appointment
        </a>
    </div>

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

    <!-- Search Form -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('appointments.index') }}" class="d-flex gap-2">
                <div class="flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Search appointments by purpose, status, patient, or doctor..." 
                               value="{{ request('search') }}"
                               style="box-shadow: none; border-radius: 0 0.375rem 0.375rem 0;">
                    </div>
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-primary px-3" onclick="clearSearchAfterSubmit()">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-user-injured"></i> Patient</th>
                                <th><i class="fas fa-user-md"></i> Doctor</th>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-clock"></i> Time</th>
                                <th><i class="fas fa-clipboard-list"></i> Purpose</th>
                                <th><i class="fas fa-info-circle"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>
                                    <strong>{{ $appointment->patient->full_name }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $appointment->doctor->full_name }}</strong>
                                    <br><small class="text-muted">{{ $appointment->doctor->getSpecializationNamesString() }}</small>
                                </td>
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
                                    <span class="badge bg-secondary">
                                        {{ $appointment->purpose ?? 'General consultation' }}
                                    </span>
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
                                        <form action="{{ route('appointments.destroy', $appointment) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirmDelete('{{ $appointment->patient->full_name }}', '{{ $appointment->doctor->full_name }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Delete Appointment">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Appointments Found</h4>
                    <p class="text-muted">Start by scheduling your first appointment.</p>
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Schedule First Appointment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmDelete(patientName, doctorName) {
    return confirm(`Are you sure you want to delete the appointment between ${patientName} and Dr. ${doctorName}?\n\nThis action cannot be undone.`);
}

function clearSearchAfterSubmit() {
    // Clear the search input after a short delay to allow form submission
    setTimeout(function() {
        document.querySelector('input[name="search"]').value = '';
    }, 100);
}

// Clear search input when page loads with search results
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && searchInput.value) {
        searchInput.value = '';
    }
});
</script>
@endsection
