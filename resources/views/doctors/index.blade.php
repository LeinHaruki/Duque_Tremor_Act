@extends('layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-md"></i> Doctors Management</h2>
        <a href="{{ route('doctors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Doctor
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
            <form method="GET" action="{{ route('doctors.index') }}" class="d-flex gap-2">
                <div class="flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Search doctors by name, contact, or specialization..." 
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
            @if($doctors->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-id-card"></i> Name</th>
                                <th><i class="fas fa-stethoscope"></i> Specializations</th>
                                <th><i class="fas fa-phone"></i> Contact</th>
                                <th><i class="fas fa-calendar-alt"></i> Appointments</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $doctor)
                            <tr>
                                <td>
                                    <strong>{{ $doctor->full_name }}</strong>
                                </td>
                                <td>
                                    @if($doctor->specializations->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($doctor->specializations as $specialization)
                                                <span class="badge bg-primary">{{ $specialization->specialization }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">No specializations</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-phone text-success"></i> {{ $doctor->contact }}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $doctor->appointments->count() }} appointments</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('doctors.show', $doctor) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('doctors.edit', $doctor) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Edit Doctor">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('doctors.destroy', $doctor) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirmDelete('{{ $doctor->full_name }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Delete Doctor">
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
                    <i class="fas fa-user-md fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Doctors Found</h4>
                    <p class="text-muted">Start by adding your first doctor to the system.</p>
                    <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add First Doctor
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmDelete(doctorName) {
    return confirm(`Are you sure you want to delete Dr. ${doctorName}?\n\nThis action cannot be undone and will also delete all associated appointments and specializations.`);
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
