<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Http\Request;

/**
 * AppointmentController
 * 
 * Handles all CRUD operations for appointments in the clinic appointment system.
 * Manages the appointment workflow and enforces business rules for status changes.
 * 
 * This controller provides:
 * - Listing appointments with search functionality
 * - Creating new appointments (default status: Pending)
 * - Viewing appointment details
 * - Editing appointments with status workflow enforcement
 * - Deleting appointments (with payment validation)
 */
class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments
     * 
     * Shows all appointments with their related patient, doctor, and payment information.
     * Includes search functionality that searches across:
     * - Appointment purpose and status
     * - Patient names (first, last, middle initial)
     * - Doctor names (first, last, middle initial)
     * 
     * Results are ordered by appointment date (most recent first).
     * 
     * @param Request $request The request containing optional search parameters
     * @return \Illuminate\View\View The appointments index view
     */
    public function index(Request $request)
    {
        // Start with base query including all relationships
        $query = Appointment::with(['patient', 'doctor', 'payment']);
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                // Search in appointment fields
                $q->where('purpose', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  // Search in related patient names
                  ->orWhereHas('patient', function($subQ) use ($search) {
                      $subQ->where('first_name', 'like', "%{$search}%")
                           ->orWhere('last_name', 'like', "%{$search}%")
                           ->orWhere('middle_initial', 'like', "%{$search}%");
                  })
                  // Search in related doctor names
                  ->orWhereHas('doctor', function($subQ) use ($search) {
                      $subQ->where('first_name', 'like', "%{$search}%")
                           ->orWhere('last_name', 'like', "%{$search}%")
                           ->orWhere('middle_initial', 'like', "%{$search}%");
                  });
            });
        }
        
        // Get appointments ordered by date (most recent first)
        $appointments = $query->orderBy('appointment_date', 'desc')->get();
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment
     * 
     * Displays the appointment creation form with:
     * - List of all patients for selection
     * - List of all doctors with their specializations
     * 
     * @return \Illuminate\View\View The appointment creation form
     */
    public function create()
    {
        // Get all patients and doctors for the form
        $patients = Patient::all();
        $doctors = Doctor::with('specializations')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created appointment
     * 
     * Creates a new appointment with validated data.
     * Enforces the appointment workflow by setting default status to "Pending".
     * 
     * Status Workflow:
     * - New appointments default to "Pending"
     * - Status changes to "Confirmed" when payment is created
     * - Status changes to "Completed" when payment is marked as paid
     * - Only "Cancelled" can be set manually
     * 
     * @param AppointmentRequest $request The validated appointment data
     * @return \Illuminate\Http\RedirectResponse Redirect with success/error message
     */
    public function store(AppointmentRequest $request)
    {
        try {
            $validated = $request->validated();
            // Ensure new appointments default to "Pending" status
            $validated['status'] = $validated['status'] ?? 'Pending';
            
            // Create the appointment
            Appointment::create($validated);
            return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create appointment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified appointment
     * 
     * Shows detailed information about a specific appointment.
     * 
     * @param Appointment $appointment The appointment to display
     * @return \Illuminate\View\View The appointment details view
     */
    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     * 
     * Displays the appointment edit form with:
     * - Current appointment data
     * - List of all patients for selection
     * - List of all doctors with their specializations
     * 
     * @param Appointment $appointment The appointment to edit
     * @return \Illuminate\View\View The appointment edit form
     */
    public function edit(Appointment $appointment)
    {
        // Get all patients and doctors for the form
        $patients = Patient::all();
        $doctors = Doctor::with('specializations')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified appointment
     * 
     * Updates an appointment with validated data while enforcing the status workflow.
     * 
     * Status Workflow Enforcement:
     * - Only "Cancelled" status can be set manually
     * - Other status changes (Pending -> Confirmed -> Completed) are handled by payment workflow
     * - Attempts to manually change status are reverted to current status
     * 
     * @param AppointmentRequest $request The validated appointment data
     * @param Appointment $appointment The appointment to update
     * @return \Illuminate\Http\RedirectResponse Redirect with success/error message
     */
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        try {
            $validated = $request->validated();
            
            // Enforce status workflow - only allow "Cancelled" to be set manually
            // Other statuses follow the workflow: Pending -> Confirmed -> Completed
            if (isset($validated['status'])) {
                $currentStatus = $appointment->status;
                $newStatus = $validated['status'];
                
                // Only allow status change to "Cancelled" manually
                // Other status changes are handled by the payment workflow
                if ($newStatus !== 'Cancelled' && $newStatus !== $currentStatus) {
                    // Revert status to current status if trying to change manually
                    $validated['status'] = $currentStatus;
                }
            }
            
            // Update the appointment
            $appointment->update($validated);
            return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update appointment: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified appointment from storage
     * 
     * Deletes an appointment with business rule validation:
     * - Cannot delete appointments that have associated payments
     * - This prevents data integrity issues and maintains audit trail
     * 
     * @param Appointment $appointment The appointment to delete
     * @return \Illuminate\Http\RedirectResponse Redirect with success/error message
     */
    public function destroy(Appointment $appointment)
    {
        try {
            // Business rule: Cannot delete appointment with existing payment
            if ($appointment->payment) {
                return redirect()->route('appointments.index')
                    ->with('error', 'Cannot delete appointment with existing payment.');
            }
            
            // Delete the appointment
            $appointment->delete();
            return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('appointments.index')
                ->with('error', 'Failed to delete appointment: ' . $e->getMessage());
        }
    }
}

