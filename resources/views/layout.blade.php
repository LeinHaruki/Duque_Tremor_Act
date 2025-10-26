{{--
    Main Layout Template - Tremor Clinic Appointment System
    
    This is the primary layout template that provides the structure for all pages
    in the clinic appointment system. It includes:
    
    Features:
    - Responsive Bootstrap 5 framework
    - Font Awesome icons
    - Dark/Light mode theme switching
    - User timezone detection and management
    - CSRF token protection
    - Navigation bar with logo and menu items
    - Footer with system information
    
    Theme System:
    - CSS variables for consistent theming
    - Dark mode support with data-theme attribute
    - Automatic theme persistence in localStorage
    - Theme toggle button in navigation
    
    Timezone Management:
    - JavaScript timezone detection
    - AJAX timezone setting to server
    - Middleware integration for local time display
    
    Navigation Structure:
    - Dashboard (main statistics and overview)
    - Patients (patient management)
    - Doctors (doctor management with specializations)
    - Appointments (appointment scheduling and management)
    - Payments (payment processing and tracking)
    
    Content Areas:
    - @yield('content') - Main page content
    - @yield('title') - Page title override
    - Flash message display for success/error notifications
    
    JavaScript Features:
    - Theme switching functionality
    - Timezone detection and setting
    - Bootstrap components initialization
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tremor Clinic - Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Theme CSS Variables -->
    <style>
        :root {
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --navbar-bg: #343a40;
            --navbar-text: #ffffff;
            --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        [data-theme="dark"] {
            --bg-primary: #121212;
            --bg-secondary: #1e1e1e;
            --text-primary: #ffffff;
            --text-secondary: #b3b3b3;
            --border-color: #333333;
            --card-bg: #2d2d2d;
            --navbar-bg: #000000;
            --navbar-text: #ffffff;
            --shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.3);
        }

        body {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar {
            background-color: var(--navbar-bg) !important;
        }

        .navbar-brand, .nav-link {
            color: var(--navbar-text) !important;
        }

        .card {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
            box-shadow: var(--shadow) !important;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .table {
            color: var(--text-primary) !important;
        }

        .table-striped > tbody > tr:nth-of-type(odd) > td {
            background-color: var(--bg-secondary);
        }

        /* Dark mode table striping - gray rows for even rows */
        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(even) > td {
            background-color: #6c757d !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(even) > td strong,
        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(even) > td .badge,
        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(even) > td small {
            color: inherit !important;
        }

        /* Dark mode table striping - dark background for odd rows */
        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > td {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }

        .form-control, .form-select {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--card-bg);
            border-color: #86b7fe;
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Enhanced input field visibility in dark mode */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: #1a1a1a !important;
            border-color: #555555 !important;
            color: #ffffff !important;
            border-width: 2px !important;
        }

        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: #1a1a1a !important;
            border-color: #86b7fe !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.5) !important;
            border-width: 2px !important;
        }

        [data-theme="dark"] .form-control:hover,
        [data-theme="dark"] .form-select:hover {
            border-color: #777777 !important;
        }

        /* Enhanced textarea visibility in dark mode */
        [data-theme="dark"] textarea.form-control {
            background-color: #1a1a1a !important;
            border-color: #555555 !important;
            color: #ffffff !important;
            border-width: 2px !important;
        }

        [data-theme="dark"] textarea.form-control:focus {
            background-color: #1a1a1a !important;
            border-color: #86b7fe !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.5) !important;
            border-width: 2px !important;
        }

        [data-theme="dark"] textarea.form-control:hover {
            border-color: #777777 !important;
        }

        /* Placeholder text styling for dark mode */
        [data-theme="dark"] .form-control::placeholder,
        [data-theme="dark"] .form-select::placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        [data-theme="dark"] .form-control::-webkit-input-placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        [data-theme="dark"] .form-control::-moz-placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        [data-theme="dark"] .form-control:-ms-input-placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        [data-theme="dark"] .form-control:-moz-placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        /* Textarea placeholder styling */
        [data-theme="dark"] textarea.form-control::placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        [data-theme="dark"] textarea.form-control::-webkit-input-placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        [data-theme="dark"] textarea.form-control::-moz-placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        [data-theme="dark"] textarea.form-control:-ms-input-placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        [data-theme="dark"] textarea.form-control:-moz-placeholder {
            color: #ffffff !important;
            opacity: 0.7;
        }

        .btn-outline-primary {
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .alert {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .theme-toggle {
            background: none;
            border: 1px solid var(--navbar-text);
            color: var(--navbar-text);
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: var(--navbar-text);
            color: var(--navbar-bg);
        }

        .theme-toggle i {
            transition: transform 0.3s ease;
        }

        .theme-toggle:hover i {
            transform: rotate(180deg);
        }

        /* Additional theme support for pages */
        .input-group-text {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-secondary) !important;
        }

        .table-dark {
            background-color: var(--navbar-bg) !important;
        }

        .table-dark th {
            color: var(--navbar-text) !important;
            border-color: var(--border-color) !important;
        }

        .table > :not(caption) > * > * {
            border-bottom-color: var(--border-color) !important;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        [data-theme="dark"] .btn-close {
            filter: invert(0) grayscale(0%) brightness(100%);
        }

        /* Badge colors for dark mode */
        [data-theme="dark"] .badge.bg-primary {
            background-color: #0d6efd !important;
        }

        [data-theme="dark"] .badge.bg-secondary {
            background-color: #6c757d !important;
        }

        [data-theme="dark"] .badge.bg-success {
            background-color: #198754 !important;
        }

        [data-theme="dark"] .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        [data-theme="dark"] .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        [data-theme="dark"] .badge.bg-info {
            background-color: #0dcaf0 !important;
            color: #000 !important;
        }

        /* Button colors for dark mode */
        [data-theme="dark"] .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        [data-theme="dark"] .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        [data-theme="dark"] .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        [data-theme="dark"] .btn-secondary:hover {
            background-color: #5c636a;
            border-color: #565e64;
        }

        [data-theme="dark"] .btn-success {
            background-color: #198754;
            border-color: #198754;
        }

        [data-theme="dark"] .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
        }

        [data-theme="dark"] .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        [data-theme="dark"] .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
        }

        [data-theme="dark"] .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        [data-theme="dark"] .btn-warning:hover {
            background-color: #ffca2c;
            border-color: #ffc720;
            color: #000;
        }

        [data-theme="dark"] .btn-info {
            background-color: #0dcaf0;
            border-color: #0dcaf0;
            color: #000;
        }

        [data-theme="dark"] .btn-info:hover {
            background-color: #3dd5f3;
            border-color: #25cff2;
            color: #000;
        }

        /* Quick action buttons in dark mode */
        [data-theme="dark"] .btn {
            color: #ffffff !important;
        }

        [data-theme="dark"] .btn:hover {
            color: #ffffff !important;
        }

        /* Ensure quick action buttons maintain white text */
        [data-theme="dark"] .btn i {
            color: #ffffff !important;
        }

        /* Dashboard card icons - white in light mode */
        .dashboard-stats .fa-2x {
            color: #ffffff !important;
        }

        /* Table values and text in dark mode */
        [data-theme="dark"] .table td,
        [data-theme="dark"] .table th {
            color: #ffffff !important;
        }

        [data-theme="dark"] .table td strong,
        [data-theme="dark"] .table td .badge {
            color: inherit !important;
        }

        /* Form labels and text in dark mode */
        [data-theme="dark"] .form-label {
            color: #ffffff !important;
        }

        [data-theme="dark"] .form-text {
            color: #b3b3b3 !important;
        }

        [data-theme="dark"] .invalid-feedback {
            color: #ff6b6b !important;
        }

        /* Card headers and titles in dark mode */
        [data-theme="dark"] .card-header {
            background-color: var(--bg-secondary) !important;
            border-bottom-color: var(--border-color) !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .card-header h2,
        [data-theme="dark"] .card-header h5 {
            color: #ffffff !important;
        }

        /* Page titles and headings in dark mode */
        [data-theme="dark"] h1,
        [data-theme="dark"] h2,
        [data-theme="dark"] h3,
        [data-theme="dark"] h4,
        [data-theme="dark"] h5,
        [data-theme="dark"] h6 {
            color: #ffffff !important;
        }

        /* Small text and muted elements in dark mode */
        [data-theme="dark"] small {
            color: #b3b3b3 !important;
        }

        /* Links in dark mode */
        [data-theme="dark"] a {
            color: #86b7fe !important;
        }

        [data-theme="dark"] a:hover {
            color: #a5c9ff !important;
        }

        /* Textarea styling in dark mode */
        [data-theme="dark"] textarea.form-control {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        [data-theme="dark"] textarea.form-control:focus {
            background-color: var(--card-bg);
            border-color: #86b7fe;
            color: var(--text-primary);
        }

        /* Additional text styling for dark mode */
        [data-theme="dark"] .text-danger {
            color: #ff6b6b !important;
        }

        [data-theme="dark"] .text-success {
            color: #51cf66 !important;
        }

        [data-theme="dark"] .text-warning {
            color: #ffd43b !important;
        }

        [data-theme="dark"] .text-info {
            color: #74c0fc !important;
        }

        [data-theme="dark"] .text-primary {
            color: #86b7fe !important;
        }

        /* List items in dark mode */
        [data-theme="dark"] ul li,
        [data-theme="dark"] ol li {
            color: #ffffff !important;
        }

        /* Alert list items */
        [data-theme="dark"] .alert ul li {
            color: inherit !important;
        }

        /* Ensure all text in cards is visible */
        [data-theme="dark"] .card-body,
        [data-theme="dark"] .card-body * {
            color: inherit;
        }

        [data-theme="dark"] .card-body p,
        [data-theme="dark"] .card-body div {
            color: #ffffff !important;
        }

        /* Patient details page styling for dark mode */
        [data-theme="dark"] .list-group-item {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .list-group-item strong {
            color: #ffffff !important;
        }

        /* Date and time input icons - make white in dark mode */
        [data-theme="dark"] input[type="date"],
        [data-theme="dark"] input[type="time"] {
            color-scheme: light !important;
        }

        [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator,
        [data-theme="dark"] input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
            opacity: 1 !important;
            cursor: pointer !important;
        }

        [data-theme="dark"] input[type="date"]::-webkit-inner-spin-button,
        [data-theme="dark"] input[type="time"]::-webkit-inner-spin-button {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
        }

        [data-theme="dark"] input[type="date"]::-webkit-clear-button,
        [data-theme="dark"] input[type="time"]::-webkit-clear-button {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
        }

        /* Force light mode icons for form controls but invert them */
        [data-theme="dark"] .form-control[type="date"],
        [data-theme="dark"] .form-control[type="time"] {
            color-scheme: light !important;
        }

        [data-theme="dark"] .form-control[type="date"]::-webkit-calendar-picker-indicator,
        [data-theme="dark"] .form-control[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
            opacity: 1 !important;
            cursor: pointer !important;
        }

        /* Additional selectors for better browser support */
        [data-theme="dark"] input[type="date"]::-webkit-datetime-edit-text,
        [data-theme="dark"] input[type="time"]::-webkit-datetime-edit-text {
            color: #ffffff !important;
        }

        [data-theme="dark"] input[type="date"]::-webkit-datetime-edit-month-field,
        [data-theme="dark"] input[type="date"]::-webkit-datetime-edit-day-field,
        [data-theme="dark"] input[type="date"]::-webkit-datetime-edit-year-field,
        [data-theme="dark"] input[type="time"]::-webkit-datetime-edit-hour-field,
        [data-theme="dark"] input[type="time"]::-webkit-datetime-edit-minute-field {
            color: #ffffff !important;
        }

        /* Firefox date/time input styling for dark mode */
        [data-theme="dark"] input[type="date"]::-moz-calendar-picker-indicator,
        [data-theme="dark"] input[type="time"]::-moz-calendar-picker-indicator {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
            opacity: 1 !important;
            cursor: pointer !important;
        }

        /* Additional browser support for date/time icons */
        [data-theme="dark"] input[type="date"]::-ms-clear,
        [data-theme="dark"] input[type="time"]::-ms-clear {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
        }

        /* Ensure calendar popup is also styled for dark mode */
        [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator:hover,
        [data-theme="dark"] input[type="time"]::-webkit-calendar-picker-indicator:hover {
            filter: invert(1) grayscale(100%) brightness(250%) !important;
        }

        /* Additional approach - try using background image for calendar icon */
        [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3e%3cpath fill-rule='evenodd' d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' clip-rule='evenodd'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            background-size: 16px 16px !important;
            width: 16px !important;
            height: 16px !important;
            filter: none !important;
        }

        [data-theme="dark"] input[type="time"]::-webkit-calendar-picker-indicator {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3e%3cpath fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z' clip-rule='evenodd'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            background-size: 16px 16px !important;
            width: 16px !important;
            height: 16px !important;
            filter: none !important;
        }

        /* Alternative approach - force white icons with multiple methods */
        [data-theme="dark"] input[type="date"]::-webkit-calendar-picker-indicator,
        [data-theme="dark"] input[type="time"]::-webkit-calendar-picker-indicator {
            /* Method 1: Filter approach */
            filter: invert(1) grayscale(100%) brightness(200%) !important;
            /* Method 2: Background image approach (will override if available) */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3e%3cpath d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            background-size: 16px 16px !important;
            width: 16px !important;
            height: 16px !important;
            opacity: 1 !important;
            cursor: pointer !important;
        }

        /* Specific time icon */
        [data-theme="dark"] input[type="time"]::-webkit-calendar-picker-indicator {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='white'%3e%3cpath d='M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z'/%3e%3c/svg%3e") !important;
        }



        /* Specialization suggestions dropdown for dark mode */
        [data-theme="dark"] .specialization-suggestions {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] .suggestion-item {
            color: #ffffff !important;
            border-bottom-color: var(--border-color) !important;
        }

        [data-theme="dark"] .suggestion-item:hover,
        [data-theme="dark"] .suggestion-item.highlighted {
            background-color: #555555 !important;
            color: #ffffff !important;
        }

        /* Dashboard tables dark mode */
        [data-theme="dark"] .table {
            color: #ffffff !important;
        }

        [data-theme="dark"] .table th {
            background-color: #343a40 !important;
            color: #ffffff !important;
            border-color: #495057 !important;
        }

        [data-theme="dark"] .table td {
            background-color: var(--card-bg) !important;
            color: #ffffff !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] .table tbody tr:nth-child(even) {
            background-color: #6c757d !important;
        }

        [data-theme="dark"] .table tbody tr:nth-child(odd) {
            background-color: #495057 !important;
        }

        [data-theme="dark"] .table tbody tr:hover {
            background-color: #555555 !important;
        }

        /* Override table-dark class in dark mode */
        [data-theme="dark"] .table-dark {
            background-color: #343a40 !important;
        }

        [data-theme="dark"] .table-dark th,
        [data-theme="dark"] .table-dark td {
            background-color: #343a40 !important;
            color: #ffffff !important;
            border-color: #495057 !important;
        }

        [data-theme="dark"] .table-dark tbody tr:nth-child(even) {
            background-color: #6c757d !important;
        }

        [data-theme="dark"] .table-dark tbody tr:nth-child(odd) {
            background-color: #495057 !important;
        }

        /* Dashboard specific table styling */
        [data-theme="dark"] .table-sm th,
        [data-theme="dark"] .table-sm td {
            color: #ffffff !important;
        }

        [data-theme="dark"] .table-sm tbody tr:nth-child(even) {
            background-color: #6c757d !important;
        }

        [data-theme="dark"] .table-sm tbody tr:nth-child(odd) {
            background-color: #495057 !important;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                @include('components.logo')
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('patients*') ? 'active' : '' }}" href="{{ route('patients.index') }}">Patients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('doctors*') ? 'active' : '' }}" href="{{ route('doctors.index') }}">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('appointments*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('payments*') ? 'active' : '' }}" href="{{ route('payments.index') }}">Payments</a>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
                            <i class="fas fa-moon" id="themeIcon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container flex-grow-1">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Tremor Clinic. All rights reserved.</p>
            <small class="text-secondary">Built with Laravel + Bootstrap</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Theme Toggle JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const body = document.body;
            
            // Get saved theme or default to light
            const savedTheme = localStorage.getItem('theme') || 'light';
            
            // Apply saved theme
            applyTheme(savedTheme);
            
            // Theme toggle functionality
            themeToggle.addEventListener('click', function() {
                const currentTheme = body.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                applyTheme(newTheme);
                localStorage.setItem('theme', newTheme);
            });
            
            function applyTheme(theme) {
                if (theme === 'dark') {
                    body.setAttribute('data-theme', 'dark');
                    themeIcon.className = 'fas fa-sun';
                    themeToggle.title = 'Switch to Light Mode';
                } else {
                    body.removeAttribute('data-theme');
                    themeIcon.className = 'fas fa-moon';
                    themeToggle.title = 'Switch to Dark Mode';
                }
            }
        });
        
        // Timezone detection and setting
        function setUserTimezone() {
            const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            
            // Send timezone to server via AJAX
            fetch('{{ route("set-timezone") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ timezone: timezone })
            }).catch(error => {
                console.log('Timezone setting failed:', error);
            });
        }
        
        // Set timezone on page load
        setUserTimezone();
    </script>
</body>
</html>
