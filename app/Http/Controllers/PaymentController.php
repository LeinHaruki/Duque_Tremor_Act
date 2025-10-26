<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\Request;

/**
 * PaymentController
 * 
 * Handles all CRUD operations for payments in the clinic appointment system.
 * Manages the payment workflow and automatically updates appointment statuses.
 * 
 * This controller provides:
 * - Listing payments with search functionality
 * - Creating new payments (triggers appointment status change to "Confirmed")
 * - Viewing payment details
 * - Editing payments
 * - Marking payments as paid (triggers appointment status change to "Completed")
 * - Deleting payments
 */
class PaymentController extends Controller
{
    /**
     * Display a listing of payments
     * 
     * Shows all payments with their related appointment, patient, and doctor information.
     * Includes comprehensive search functionality that searches across:
     * - Payment amount, method, and status
     * - Related appointment purpose
     * - Related patient names (first, last, middle initial)
     * - Related doctor names (first, last, middle initial)
     * 
     * Results are ordered by creation date (most recent first).
     * 
     * @param Request $request The request containing optional search parameters
     * @return \Illuminate\View\View The payments index view
     */
    public function index(Request $request)
    {
        // Start with base query including all relationships
        $query = Payment::with(['appointment.patient', 'appointment.doctor']);
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                // Search in payment fields
                $q->where('amount', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  // Search in related appointment and nested relationships
                  ->orWhereHas('appointment', function($subQ) use ($search) {
                      $subQ->where('purpose', 'like', "%{$search}%")
                           // Search in related patient names
                           ->orWhereHas('patient', function($patientQ) use ($search) {
                               $patientQ->where('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%")
                                        ->orWhere('middle_initial', 'like', "%{$search}%");
                           })
                           // Search in related doctor names
                           ->orWhereHas('doctor', function($doctorQ) use ($search) {
                               $doctorQ->where('first_name', 'like', "%{$search}%")
                                       ->orWhere('last_name', 'like', "%{$search}%")
                                       ->orWhere('middle_initial', 'like', "%{$search}%");
                           });
                  });
            });
        }
        
        // Get payments ordered by creation date (most recent first)
        $payments = $query->orderBy('created_at', 'desc')->get();
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment
     * 
     * Displays the payment creation form with:
     * - List of appointments that don't have payments yet
     * - Includes appointment that was selected in previous request (for error handling)
     * - Each appointment shows patient and doctor information
     * 
     * @return \Illuminate\View\View The payment creation form
     */
    public function create()
    {
        // Get appointments that don't have payments yet, or if we're redirecting back with errors,
        // include the appointment that was selected (in case it was just created)
        $appointments = Appointment::whereDoesntHave('payment')
            ->orWhere(function($query) {
                // Include appointment if it was selected in the previous request
                if (old('appointment_id')) {
                    $query->where('id', old('appointment_id'));
                }
            })
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date', 'desc')
            ->get();
            
        return view('payments.create', compact('appointments'));
    }

    /**
     * Store a newly created payment
     * 
     * Creates a new payment and automatically updates the related appointment status.
     * 
     * Payment Workflow:
     * - New payments default to "Unpaid" status (can be changed to "Paid" by user)
     * - If payment status is "Paid": appointment status changes from "Pending" to "Completed"
     * - If payment status is "Unpaid": appointment status changes from "Pending" to "Confirmed"
     * - This enforces the appointment workflow: Pending -> Confirmed -> Completed
     * 
     * @param PaymentRequest $request The validated payment data
     * @return \Illuminate\Http\RedirectResponse Redirect with success/error message
     */
    public function store(PaymentRequest $request)
    {
        try {
            $validated = $request->validated();
            // Use the status from the form (defaults to "Unpaid" from the form)
            $validated['status'] = $validated['status'] ?? 'Unpaid';
            
            // Create the payment
            $payment = Payment::create($validated);
            
            // Update appointment status based on payment status
            // This enforces the appointment workflow
            $appointment = $payment->appointment;
            if ($appointment && $appointment->status === 'Pending') {
                if ($payment->status === 'Paid') {
                    // If payment is "Paid", appointment goes directly to "Completed"
                    $appointment->update(['status' => 'Completed']);
                } else {
                    // If payment is "Unpaid", appointment goes to "Confirmed"
                    $appointment->update(['status' => 'Confirmed']);
                }
            }
            
            $message = $payment->status === 'Paid' 
                ? 'Payment recorded successfully and appointment marked as completed.'
                : 'Payment recorded successfully and appointment confirmed.';
                
            return redirect()->route('payments.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified payment
     * 
     * Shows detailed information about a specific payment.
     * 
     * @param Payment $payment The payment to display
     * @return \Illuminate\View\View The payment details view
     */
    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment
     * 
     * Displays the payment edit form with current payment data.
     * 
     * @param Payment $payment The payment to edit
     * @return \Illuminate\View\View The payment edit form
     */
    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment
     * 
     * Updates a payment with validated data.
     * 
     * @param PaymentRequest $request The validated payment data
     * @param Payment $payment The payment to update
     * @return \Illuminate\Http\RedirectResponse Redirect with success/error message
     */
    public function update(PaymentRequest $request, Payment $payment)
    {
        try {
            $validated = $request->validated();
            $payment->update($validated);
            return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update payment: ' . $e->getMessage());
        }
    }

    /**
     * Mark a payment as paid
     * 
     * Updates a payment status to "Paid" and automatically updates the related
     * appointment status to "Completed".
     * 
     * Payment Workflow:
     * - Payment status changes to "Paid"
     * - Appointment status changes to "Completed"
     * - This completes the appointment workflow: Pending -> Confirmed -> Completed
     * 
     * @param Payment $payment The payment to mark as paid
     * @return \Illuminate\Http\RedirectResponse Redirect with success/error message
     */
    public function markAsPaid(Payment $payment)
    {
        try {
            // Update payment status to Paid
            $payment->update(['status' => 'Paid']);
            
            // Update appointment status to Completed
            // This completes the appointment workflow
            $payment->appointment->update(['status' => 'Completed']);
            
            return redirect()->route('payments.index')
                ->with('success', 'Payment marked as paid and appointment status updated to completed.');
        } catch (\Exception $e) {
            return redirect()->route('payments.index')
                ->with('error', 'Failed to mark payment as paid: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified payment from storage
     * 
     * Deletes a payment from the database and updates the corresponding appointment
     * status if the payment was paid.
     * 
     * Payment Deletion Workflow:
     * - If payment status is "Paid", update the corresponding appointment status to "Pending"
     * - This reverses the appointment workflow when a paid payment is removed
     * - Then delete the payment record
     * 
     * @param Payment $payment The payment to delete
     * @return \Illuminate\Http\RedirectResponse Redirect with success/error message
     */
    public function destroy(Payment $payment)
    {
        try {
            // Check if the payment is paid before deletion
            $wasPaid = $payment->status === 'Paid';
            
            // Get the appointment before deleting the payment
            $appointment = $payment->appointment;
            
            // Delete the payment
            $payment->delete();
            
            // If the payment was paid, update the appointment status back to "Pending"
            if ($wasPaid && $appointment) {
                $appointment->update(['status' => 'Pending']);
            }
            
            $message = $wasPaid 
                ? 'Payment deleted successfully and appointment status updated to pending.'
                : 'Payment deleted successfully.';
                
            return redirect()->route('payments.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('payments.index')
                ->with('error', 'Failed to delete payment: ' . $e->getMessage());
        }
    }
}

