@extends('layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-credit-card"></i> Payments Management</h2>
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Payment
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
            <form method="GET" action="{{ route('payments.index') }}" class="d-flex gap-2">
                <div class="flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Search payments by amount, method, status, patient, or doctor..." 
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
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-calendar-check"></i> Appointment</th>
                                <th><i class="fas fa-dollar-sign"></i> Amount</th>
                                <th><i class="fas fa-credit-card"></i> Method</th>
                                <th><i class="fas fa-info-circle"></i> Status</th>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <strong>{{ $payment->appointment->purpose ?? 'General consultation' }}</strong>
                                    <br><small class="text-muted">{{ $payment->appointment->patient->full_name }}</small>
                                    <br><small class="text-muted">Dr. {{ $payment->appointment->doctor->full_name }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success fs-6">${{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($payment->method == 'Cash') bg-success
                                        @elseif($payment->method == 'Card') bg-primary
                                        @else bg-info
                                        @endif">
                                        <i class="fas 
                                            @if($payment->method == 'Cash') fa-money-bill
                                            @elseif($payment->method == 'Card') fa-credit-card
                                            @else fa-globe
                                            @endif"></i>
                                        {{ $payment->method }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($payment->status == 'Paid') bg-success
                                        @else bg-danger
                                        @endif">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $payment->created_at->format('M d, Y') }}
                                        <br>{{ $payment->created_at->format('h:i A') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('payments.show', $payment) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($payment->status != 'Paid')
                                            <form action="{{ route('payments.mark-paid', $payment) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirmMarkPaid('{{ $payment->appointment->patient->full_name }}', '${{ number_format($payment->amount, 2) }}')">
                                                @csrf @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-success btn-sm" 
                                                        title="Mark as Paid">
                                                    <i class="fas fa-check"></i> Mark Paid
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('payments.destroy', $payment) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirmDelete('{{ $payment->appointment->patient->full_name }}', '${{ number_format($payment->amount, 2) }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Delete Payment">
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
                    <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Payments Found</h4>
                    <p class="text-muted">Start by recording your first payment.</p>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Record First Payment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmDelete(patientName, amount) {
    return confirm(`Are you sure you want to delete the payment of ${amount} for ${patientName}?\n\nThis action cannot be undone.`);
}

function confirmMarkPaid(patientName, amount) {
    return confirm(`Mark payment of ${amount} for ${patientName} as PAID?\n\nThis will also update the appointment status.`);
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
