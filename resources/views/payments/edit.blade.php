@extends('layout')

@section('content')
<h2>Edit Payment</h2>

<form action="{{ route('payments.update', $payment) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Appointment</label>
        <select name="appointment_id" class="form-control">
            @foreach($appointments as $appointment)
                <option value="{{ $appointment->id }}" {{ $payment->appointment_id == $appointment->id ? 'selected' : '' }}>
                    {{ $appointment->purpose ?? 'General consultation' }} - {{ $appointment->patient->full_name }} with Dr. {{ $appointment->doctor->full_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Amount</label>
        <input type="number" name="amount" value="{{ $payment->amount }}" class="form-control" step="0.01">
    </div>
    <div class="mb-3">
        <label>Method</label>
        <select name="method" class="form-control">
            <option {{ $payment->method == 'Cash' ? 'selected' : '' }}>Cash</option>
            <option {{ $payment->method == 'Card' ? 'selected' : '' }}>Card</option>
            <option {{ $payment->method == 'Insurance' ? 'selected' : '' }}>Insurance</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option {{ $payment->status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option {{ $payment->status == 'Paid' ? 'selected' : '' }}>Paid</option>
        </select>
    </div>
    <button class="btn btn-success">Update</button>
    <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back</a>
</form>
@endsection
