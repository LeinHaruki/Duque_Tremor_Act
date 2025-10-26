@extends('layout')

@section('content')
<h2>Appointment Details</h2>

<ul class="list-group mb-3">
    <li class="list-group-item"><strong>Patient:</strong> {{ $appointment->patient->full_name }}</li>
    <li class="list-group-item"><strong>Doctor:</strong> {{ $appointment->doctor->full_name }}</li>
    <li class="list-group-item"><strong>Date:</strong> {{ $appointment->appointment_date }}</li>
    <li class="list-group-item"><strong>Time:</strong> {{ $appointment->appointment_time }}</li>
    <li class="list-group-item"><strong>Purpose:</strong> {{ $appointment->purpose ?? 'General consultation' }}</li>
    <li class="list-group-item"><strong>Status:</strong> {{ $appointment->status }}</li>
</ul>

<a href="{{ route('appointments.index') }}" class="btn btn-secondary">Back</a>
@endsection
