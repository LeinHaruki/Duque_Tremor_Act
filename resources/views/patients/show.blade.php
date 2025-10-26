@extends('layout')

@section('content')
<h2>Patient Details</h2>

<ul class="list-group mb-3">
    <li class="list-group-item"><strong>Name:</strong> {{ $patient->full_name }}</li>
    <li class="list-group-item"><strong>Age:</strong> {{ $patient->age }}</li>
    <li class="list-group-item"><strong>Gender:</strong> {{ $patient->gender }}</li>
    <li class="list-group-item"><strong>Contact:</strong> {{ $patient->contact }}</li>
    <li class="list-group-item"><strong>Address:</strong> {{ $patient->address }}</li>
</ul>

<a href="{{ route('patients.index') }}" class="btn btn-secondary">Back</a>
@endsection
