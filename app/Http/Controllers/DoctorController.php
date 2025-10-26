<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Http\Requests\DoctorRequest;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('specializations');
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_initial', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhereHas('specializations', function($subQ) use ($search) {
                      $subQ->where('specialization', 'like', "%{$search}%");
                  });
            });
        }
        
        $doctors = $query->orderBy('first_name')->get();
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(DoctorRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Create the doctor
            $doctor = Doctor::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_initial' => $validated['middle_initial'] ?? null,
                'contact' => $validated['contact']
            ]);

            // Add specializations
            if (isset($validated['specializations']) && is_array($validated['specializations'])) {
                foreach ($validated['specializations'] as $spec) {
                    if (!empty(trim($spec))) {
                        $doctor->specializations()->create(['specialization' => trim($spec)]);
                    }
                }
            }

            return redirect()->route('doctors.index')->with('success', 'Doctor created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create doctor: ' . $e->getMessage());
        }
    }


    public function show(Doctor $doctor)
    {
        $doctor->load('specializations');
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load('specializations');
        return view('doctors.edit', compact('doctor'));
    }

    public function update(DoctorRequest $request, Doctor $doctor)
    {
        try {
            $validated = $request->validated();
            
            // Update doctor basic info
            $doctor->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_initial' => $validated['middle_initial'] ?? null,
                'contact' => $validated['contact']
            ]);

            // Update specializations
            if (isset($validated['specializations']) && is_array($validated['specializations'])) {
                // Delete existing specializations
                $doctor->specializations()->delete();
                
                // Add new specializations
                foreach ($validated['specializations'] as $spec) {
                    if (!empty(trim($spec))) {
                        $doctor->specializations()->create(['specialization' => trim($spec)]);
                    }
                }
            }

            return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update doctor: ' . $e->getMessage());
        }
    }


    public function destroy(Doctor $doctor)
    {
        try {
            // Check if doctor has appointments
            if ($doctor->appointments()->count() > 0) {
                return redirect()->route('doctors.index')
                    ->with('error', 'Cannot delete doctor with existing appointments.');
            }
            
            $doctor->delete();
            return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('doctors.index')
                ->with('error', 'Failed to delete doctor: ' . $e->getMessage());
        }
    }
}

