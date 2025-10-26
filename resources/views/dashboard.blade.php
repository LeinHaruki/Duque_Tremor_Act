{{--
    Dashboard View - Tremor Clinic Appointment System
    
    This view displays the main dashboard with key statistics and information
    for the clinic appointment system. It provides an overview of:
    
    Statistics Cards:
    - Total Patients: Count of all registered patients
    - Total Doctors: Count of all registered doctors
    - Upcoming Appointments: Count of pending/confirmed appointments from today onwards
    - Unpaid Payments: Count of payments with "Unpaid" status
    
    Data Tables:
    - Today's Appointments: List of appointments scheduled for today
    - Recent Unpaid Payments: List of 5 most recent unpaid payments
    
    Features:
    - Equal height cards for visual consistency
    - Responsive design for mobile and desktop
    - White text/icons for both light and dark modes
    - Quick action buttons for common tasks
    - Links to detailed views (upcoming appointments, unpaid payments)
    
    Data Sources:
    - $totalPatients: Total count from patients table
    - $totalDoctors: Total count from doctors table
    - $upcomingAppointments: Count of appointments with status Pending/Confirmed, date >= today
    - $unpaidPayments: Count of payments with status Unpaid
    - $todayAppointments: List of appointments for today with patient/doctor details
    - $recentUnpaidPayments: List of 5 most recent unpaid payments with appointment details
    
    Styling:
    - Custom CSS for equal height cards
    - Flexbox layout for card content distribution
    - White text enforcement for dashboard statistics
    - Responsive adjustments for mobile devices
--}}
@extends('layout')

@section('content')
<style>
/* Dashboard Cards Equal Height */
.dashboard-stats .card {
    height: 100%;
    min-height: 180px;
}

.dashboard-stats .card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.dashboard-stats .card-content {
    flex-grow: 1;
}

.dashboard-stats .card-footer-link {
    margin-top: auto;
}

/* Ensure consistent spacing */
.dashboard-stats .card-title {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    color: #ffffff !important;
}

.dashboard-stats h3 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 0;
    color: #ffffff !important;
}

.dashboard-stats .fa-2x {
    font-size: 2.5rem !important;
    color: #ffffff !important;
}

/* Ensure all text in dashboard cards is white */
.dashboard-stats .card-body {
    color: #ffffff !important;
}

.dashboard-stats .card-body * {
    color: #ffffff !important;
}

.dashboard-stats .card-body small {
    color: #ffffff !important;
}

.dashboard-stats .card-body a {
    color: #ffffff !important;
}

.dashboard-stats .card-body a:hover {
    color: #ffffff !important;
    opacity: 0.8;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .dashboard-stats .card {
        min-height: 160px;
    }
    
    .dashboard-stats h3 {
        font-size: 2rem;
        color: #ffffff !important;
    }
    
    .dashboard-stats .fa-2x {
        font-size: 2rem !important;
        color: #ffffff !important;
    }
    
    .dashboard-stats .card-title {
        color: #ffffff !important;
    }
}
</style>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tachometer-alt"></i> Clinic Dashboard</h2>
        <small class="text-muted">Last updated: {{ now()->format('M d, Y h:i A') }}</small>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 dashboard-stats">
        <!-- Patients -->
        <div class="col-md-3">
            <div class="card text-white mb-3 shadow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="card-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title"><i class="fas fa-user-injured"></i> Total Patients</h5>
                                <h3>{{ $totalPatients }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-injured fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer-link">
                        <a href="{{ route('patients.index') }}" class="text-white text-decoration-none">
                            <small>View all patients <i class="fas fa-arrow-right"></i></small>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doctors -->
        <div class="col-md-3">
            <div class="card text-white mb-3 shadow" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="card-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title"><i class="fas fa-user-md"></i> Total Doctors</h5>
                                <h3>{{ $totalDoctors }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-md fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer-link">
                        <a href="{{ route('doctors.index') }}" class="text-white text-decoration-none">
                            <small>View all doctors <i class="fas fa-arrow-right"></i></small>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-md-3">
            <div class="card text-white mb-3 shadow" style="background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);">
                <div class="card-body">
                    <div class="card-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title"><i class="fas fa-calendar-check"></i> Upcoming Appointments</h5>
                                <h3>{{ $upcomingAppointments }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-calendar-check fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer-link">
                        <a href="{{ route('dashboard.upcoming-appointments') }}" class="text-white text-decoration-none">
                            <small>View upcoming appointments <i class="fas fa-arrow-right"></i></small>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unpaid Payments -->
        <div class="col-md-3">
            <div class="card text-white mb-3 shadow" style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);">
                <div class="card-body">
                    <div class="card-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title"><i class="fas fa-credit-card"></i> Unpaid Payments</h5>
                                <h3>{{ $unpaidPayments }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-credit-card fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer-link">
                        <a href="{{ route('dashboard.unpaid-payments') }}" class="text-white text-decoration-none">
                            <small>View unpaid payments <i class="fas fa-arrow-right"></i></small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Information -->
    <div class="row">
        <!-- Today's Appointments -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0"><i class="fas fa-calendar-day"></i> Today's Appointments</h5>
                </div>
                <div class="card-body">
                    @if($todayAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayAppointments as $appointment)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                </span>
                                            </td>
                                            <td>{{ $appointment->patient->full_name }}</td>
                                            <td>{{ $appointment->doctor->full_name }}</td>
                                            <td>
                                                <span class="badge text-white" style="
                                                    @if($appointment->status == 'Completed') background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                                                    @elseif($appointment->status == 'Confirmed') background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                                    @elseif($appointment->status == 'Cancelled') background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
                                                    @else background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
                                                    @endif">
                                                    {{ $appointment->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No appointments scheduled for today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Unpaid Payments -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Recent Unpaid Payments</h5>
                </div>
                <div class="card-body">
                    @if($recentUnpaidPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUnpaidPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->appointment->patient->full_name }}</td>
                                            <td>{{ $payment->appointment->doctor->full_name }}</td>
                                            <td>
                                                <span class="badge text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">${{ number_format($payment->amount, 2) }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $payment->created_at->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('dashboard.unpaid-payments') }}" class="btn btn-sm text-white" style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); border: none;">
                                <i class="fas fa-credit-card"></i> View All Unpaid Payments
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-muted mb-0">All payments are up to date!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('patients.create') }}" class="btn w-100 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                <i class="fas fa-user-plus"></i> Add Patient
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('doctors.create') }}" class="btn w-100 text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none;">
                                <i class="fas fa-user-md"></i> Add Doctor
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('appointments.create') }}" class="btn w-100 text-white" style="background: linear-gradient(135deg, #38a169 0%, #2f855a 100%); border: none;">
                                <i class="fas fa-calendar-plus"></i> Schedule Appointment
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('payments.create') }}" class="btn w-100 text-white" style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); border: none;">
                                <i class="fas fa-credit-card"></i> Record Payment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
