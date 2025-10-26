<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * DashboardController
 * 
 * Handles the main dashboard functionality and displays key statistics
 * and information for the clinic appointment system.
 * 
 * This controller provides:
 * - Main dashboard with statistics cards
 * - Upcoming appointments listing
 * - Unpaid payments listing
 * - Timezone management for user's local time
 */
class DashboardController extends Controller
{
    /**
     * Display the main dashboard
     * 
     * This method gathers all the key statistics and data needed for the dashboard:
     * - Total number of patients and doctors
     * - Count of upcoming appointments (Pending/Confirmed status)
     * - Count of unpaid payments
     * - Today's appointments with patient and doctor details
     * - Recent unpaid payments with related appointment information
     * 
     * The data is organized to provide a comprehensive overview of the clinic's
     * current status and immediate priorities.
     * 
     * @return \Illuminate\View\View The dashboard view with all statistics
     */
    public function index()
    {
        // Get total counts for statistics cards
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        
        // Get upcoming appointments count (Pending and Confirmed status, from today onwards)
        // Uses user's local timezone for accurate "today" calculation
        $upcomingAppointments = Appointment::whereIn('status', ['Pending', 'Confirmed'])
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->count();
            
        // Get unpaid payments count
        $unpaidPayments = Payment::where('status', 'Unpaid')->count();
        
        // Get today's appointments with related patient and doctor information
        // Ordered by appointment time for chronological display
        $todayAppointments = Appointment::whereDate('appointment_date', now()->toDateString())
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_time')
            ->get();
            
        // Get recent unpaid payments with appointment, patient, and doctor details
        // Limited to 5 most recent for dashboard display
        $recentUnpaidPayments = Payment::where('status', 'Unpaid')
            ->with(['appointment.patient', 'appointment.doctor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Pass all data to the dashboard view
        return view('dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'upcomingAppointments',
            'unpaidPayments',
            'todayAppointments',
            'recentUnpaidPayments'
        ));
    }

    /**
     * Display all upcoming appointments
     * 
     * Shows a detailed list of all appointments that are:
     * - Status: Pending or Confirmed
     * - Date: Today or future dates
     * 
     * Includes patient and doctor information for each appointment.
     * Ordered by appointment date and time for chronological display.
     * 
     * @return \Illuminate\View\View The upcoming appointments view
     */
    public function upcomingAppointments()
    {
        // Get all upcoming appointments with patient and doctor details
        $upcomingAppointments = Appointment::whereIn('status', ['Pending', 'Confirmed'])
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->with(['patient', 'doctor'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        return view('dashboard.upcoming-appointments', compact('upcomingAppointments'));
    }

    /**
     * Display all unpaid payments
     * 
     * Shows a detailed list of all payments with "Unpaid" status.
     * Includes related appointment, patient, and doctor information.
     * Ordered by creation date (most recent first).
     * 
     * @return \Illuminate\View\View The unpaid payments view
     */
    public function unpaidPayments()
    {
        // Get all unpaid payments with appointment, patient, and doctor details
        $unpaidPayments = Payment::where('status', 'Unpaid')
            ->with(['appointment.patient', 'appointment.doctor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.unpaid-payments', compact('unpaidPayments'));
    }

    /**
     * Set user's timezone for local time display
     * 
     * This method handles AJAX requests from the frontend to set the user's
     * local timezone. The timezone is validated against PHP's timezone list
     * and stored in the session for use throughout the application.
     * 
     * This ensures that all date/time operations use the user's local time
     * instead of the server's timezone.
     * 
     * @param Request $request The request containing the timezone
     * @return \Illuminate\Http\JsonResponse Success or error response
     */
    public function setTimezone(Request $request)
    {
        $timezone = $request->input('timezone');
        
        // Validate timezone against PHP's list of valid timezones
        if (in_array($timezone, timezone_identifiers_list())) {
            // Store timezone in session for middleware to use
            session(['timezone' => $timezone]);
            return response()->json(['success' => true, 'timezone' => $timezone]);
        }
        
        // Return error if timezone is invalid
        return response()->json(['success' => false, 'message' => 'Invalid timezone']);
    }
}
