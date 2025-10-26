<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\PatientRequest;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_initial', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        $patients = $query->orderBy('first_name')->get();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(PatientRequest $request)
    {
        try {
            Patient::create($request->validated());
            return redirect()->route('patients.index')->with('success', 'Patient added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create patient: ' . $e->getMessage());
        }
    }

    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(PatientRequest $request, Patient $patient)
    {
        try {
            $patient->update($request->validated());
            return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update patient: ' . $e->getMessage());
        }
    }

    public function destroy(Patient $patient)
    {
        try {
            // Check if patient has appointments
            if ($patient->appointments()->count() > 0) {
                return redirect()->route('patients.index')
                    ->with('error', 'Cannot delete patient with existing appointments.');
            }
            
            $patient->delete();
            return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('patients.index')
                ->with('error', 'Failed to delete patient: ' . $e->getMessage());
        }
    }
}
