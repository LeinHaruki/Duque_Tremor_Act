@extends('layout')

@section('content')
<h2>Payment Details</h2>

<ul class="list-group mb-3">
    <li class="list-group-item"><strong>Appointment:</strong> {{ $payment->appointment->purpose ?? 'General consultation' }} - {{ $payment->appointment->patient->full_name }}</li>
    <li class="list-group-item"><strong>Amount:</strong> {{ $payment->amount }}</li>
    <li class="list-group-item"><strong>Method:</strong> {{ $payment->method }}</li>
    <li class="list-group-item"><strong>Status:</strong> {{ $payment->status }}</li>
</ul>

<a href="{{ route('payments.index') }}" class="btn btn-secondary">Back</a>
@endsection
