{{-- Tremor Clinic Logo Component --}}
<div class="tremor-logo d-flex align-items-center">
    {{-- Clipboard Icon with Checkmark --}}
    <div class="logo-icon me-3">
        <svg width="40" height="50" viewBox="0 0 40 50" fill="none" xmlns="http://www.w3.org/2000/svg">
            {{-- Clipboard Background --}}
            <rect x="8" y="4" width="24" height="36" rx="3" stroke="#20C997" stroke-width="2" fill="none"/>
            {{-- Clipboard Top Clip --}}
            <rect x="6" y="2" width="28" height="6" rx="3" fill="#20C997"/>
            {{-- Checkmark --}}
            <path d="M16 22L20 26L28 18" stroke="#20C997" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    
    {{-- Clinic Name and Tagline --}}
    <div class="logo-text">
        <div class="clinic-name fw-bold">
            <div class="tremor-text">TREMOR</div>
            <div class="clinic-text">CLINIC</div>
        </div>
        <div class="tagline small">ALWAYS CHECK YOUR HEALTH</div>
    </div>
</div>

<style>
.tremor-logo .clinic-name {
    line-height: 1.1;
    font-size: 1.1rem;
    letter-spacing: 0.5px;
}

.tremor-logo .tremor-text {
    color: #20C997 !important;
}

.tremor-logo .clinic-text {
    color: #17A2B8 !important;
}

.tremor-logo .tagline {
    font-size: 0.7rem;
    letter-spacing: 0.3px;
    margin-top: 2px;
    color: #20C997 !important;
    text-shadow: 0 0 3px rgba(32, 201, 151, 0.3);
}

.tremor-logo .logo-icon svg {
    filter: drop-shadow(0 0 3px rgba(32, 201, 151, 0.3));
}

/* Ensure logo colors remain consistent in both themes */
[data-theme="dark"] .tremor-logo .tremor-text,
[data-theme="dark"] .tremor-logo .clinic-text,
[data-theme="dark"] .tremor-logo .tagline {
    color: inherit !important;
}

/* Override any theme-based text color changes */
.tremor-logo * {
    color: inherit !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tremor-logo .clinic-name {
        font-size: 0.9rem;
    }
    
    .tremor-logo .tagline {
        font-size: 0.6rem;
    }
    
    .tremor-logo .logo-icon svg {
        width: 30px;
        height: 38px;
    }
}
</style>
