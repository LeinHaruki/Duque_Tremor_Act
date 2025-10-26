@extends('layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-exclamation-triangle"></i> Unpaid Payments</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="fas fa-credit-card"></i> 
                All Unpaid Payments ({{ $unpaidPayments->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($unpaidPayments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-user-injured"></i> Patient</th>
                                <th><i class="fas fa-user-md"></i> Doctor</th>
                                <th><i class="fas fa-dollar-sign"></i> Amount</th>
                                <th><i class="fas fa-credit-card"></i> Payment Method</th>
                                <th><i class="fas fa-calendar"></i> Created Date</th>
                                <th><i class="fas fa-info-circle"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unpaidPayments as $payment)
                                <tr>
                                    <td>
                                        <strong>{{ $payment->appointment->patient->full_name }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $payment->appointment->doctor->full_name }}</strong>
                                        <br><small class="text-muted">{{ $payment->appointment->doctor->getSpecializationNamesString() }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success fs-6">${{ number_format($payment->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $payment->method }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $payment->created_at->format('M d, Y') }}
                                            <br>{{ $payment->created_at->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $payment->status }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('payments.show', $payment) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i> View
                                            </a>
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
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Information -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-calculator"></i> Payment Summary</h6>
                                <p class="mb-1"><strong>Total Unpaid Amount:</strong></p>
                                <h4 class="text-danger">${{ number_format($unpaidPayments->sum('amount'), 2) }}</h4>
                                <p class="mb-0"><strong>Number of Unpaid Payments:</strong> {{ $unpaidPayments->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-chart-pie"></i> Payment Methods</h6>
                                @php
                                    $methods = $unpaidPayments->groupBy('method');
                                @endphp
                                @foreach($methods as $method => $payments)
                                    <p class="mb-1">
                                        <span class="badge bg-info">{{ $method }}</span>
                                        <strong>{{ $payments->count() }}</strong> payments
                                        <span class="text-muted">(${{ number_format($payments->sum('amount'), 2) }})</span>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h4 class="text-success">All Payments Are Up to Date!</h4>
                    <p class="text-muted">There are no unpaid payments in the system.</p>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Record New Payment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmMarkPaid(patientName, amount) {
    return confirm(`Are you sure you want to mark the payment of $${amount} for ${patientName} as PAID?\n\nThis will also update the appointment status to COMPLETED.`);
}
</script>
@endsection
