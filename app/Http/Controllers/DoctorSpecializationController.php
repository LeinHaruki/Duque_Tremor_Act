<?php

namespace App\Http\Controllers;

use App\Models\DoctorSpecialization;
use App\Models\Doctor;
use App\Http\Requests\DoctorSpecializationRequest;
use Illuminate\Http\Request;

class DoctorSpecializationController extends Controller
{
    public function index()
    {
        $specializations = DoctorSpecialization::with('doctor')->get();
        return view('doctor-specializations.index', compact('specializations'));
    }

    public function create()
    {
        $doctors = Doctor::all();
        return view('doctor-specializations.create', compact('doctors'));
    }

    public function store(DoctorSpecializationRequest $request)
    {
        try {
            DoctorSpecialization::create($request->validated());
            return redirect()->route('doctor-specializations.index')->with('success', 'Doctor specialization added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create doctor specialization: ' . $e->getMessage());
        }
    }

    public function show(DoctorSpecialization $specialization)
    {
        return view('doctor-specializations.show', compact('specialization'));
    }

    public function edit(DoctorSpecialization $specialization)
    {
        $doctors = Doctor::all();
        return view('doctor-specializations.edit', compact('specialization', 'doctors'));
    }

    public function update(DoctorSpecializationRequest $request, DoctorSpecialization $specialization)
    {
        try {
            $specialization->update($request->validated());
            return redirect()->route('doctor-specializations.index')->with('success', 'Doctor specialization updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update doctor specialization: ' . $e->getMessage());
        }
    }

    public function destroy(DoctorSpecialization $specialization)
    {
        try {
            $specialization->delete();
            return redirect()->route('doctor-specializations.index')->with('success', 'Doctor specialization deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('doctor-specializations.index')
                ->with('error', 'Failed to delete doctor specialization: ' . $e->getMessage());
        }
    }
}
