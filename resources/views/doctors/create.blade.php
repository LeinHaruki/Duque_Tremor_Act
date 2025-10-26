@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Add New Doctor</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('doctors.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                       value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="middle_initial" class="form-label">Middle Initial</label>
                                <input type="text" name="middle_initial" id="middle_initial" class="form-control @error('middle_initial') is-invalid @enderror" 
                                       value="{{ old('middle_initial') }}" maxlength="1" style="text-transform:uppercase">
                                @error('middle_initial')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                       value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" name="contact" id="contact" class="form-control @error('contact') is-invalid @enderror" 
                                   value="{{ old('contact') }}" required>
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="specializations" class="form-label">Specializations <span class="text-danger">*</span></label>
                            <div id="specializations-container">
                                <div class="specialization-input mb-2 d-flex">
                                    <div class="position-relative flex-grow-1">
                                        <input type="text" name="specializations[]" class="form-control specialization-input-field @error('specializations') is-invalid @enderror" 
                                               placeholder="Enter specialization" value="{{ old('specializations.0') }}" required autocomplete="off">
                                        <div class="specialization-suggestions position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow-sm" style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;"></div>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-specialization" style="display: none;">
                                        <i class="fas fa-times"></i> Remove
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="add-specialization" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-plus"></i> Add Specialization
                            </button>
                            @error('specializations')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Doctor
                            </button>
                            <a href="{{ route('doctors.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('specializations-container');
    const addBtn = document.getElementById('add-specialization');
    
    // List of available specializations
    const specializations = [
        'Internist', 'Cardiologist', 'Endocrinologist', 'Gastroenterologist', 'Nephrologist',
        'Pulmonologist', 'Hematologist', 'Rheumatologist', 'Infectious Disease Specialist',
        'Neurologist', 'Psychiatrist', 'Neurosurgeon', 'Pediatrician', 'Pediatric Cardiologist',
        'Pediatric Neurologist', 'Obstetrician', 'Gynecologist', 'OB-GYN', 'General Surgeon',
        'Orthopedic Surgeon', 'Plastic Surgeon', 'Cardiothoracic Surgeon', 'ENT (Otolaryngologist)',
        'Urologist', 'Colorectal Surgeon', 'Ophthalmologist', 'Optometrist', 'Audiologist',
        'Dentist', 'Orthodontist', 'Oral Surgeon', 'Periodontist', 'Endodontist',
        'Dermatologist', 'Allergist / Immunologist', 'Oncologist', 'Pathologist', 'Radiologist',
        'Anesthesiologist', 'Emergency Medicine Specialist', 'Family Medicine Doctor',
        'Geriatrician', 'Occupational Medicine Specialist', 'Sports Medicine Specialist'
    ];
    
    // Add specialization
    addBtn.addEventListener('click', function() {
        const newInput = document.createElement('div');
        newInput.className = 'specialization-input mb-2 d-flex';
        newInput.innerHTML = `
            <div class="position-relative flex-grow-1">
                <input type="text" name="specializations[]" class="form-control specialization-input-field" placeholder="Enter specialization" required autocomplete="off">
                <div class="specialization-suggestions position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow-sm" style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;"></div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-specialization">
                <i class="fas fa-times"></i> Remove
            </button>
        `;
        container.appendChild(newInput);
        updateRemoveButtons();
        setupAutocomplete(newInput.querySelector('.specialization-input-field'));
    });
    
    // Remove specialization
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-specialization')) {
            e.target.closest('.specialization-input').remove();
            updateRemoveButtons();
        }
    });
    
    function updateRemoveButtons() {
        const inputs = container.querySelectorAll('.specialization-input');
        inputs.forEach((input, index) => {
            const removeBtn = input.querySelector('.remove-specialization');
            if (inputs.length === 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'block';
            }
        });
    }
    
    function setupAutocomplete(input) {
        const suggestionsDiv = input.parentElement.querySelector('.specialization-suggestions');
        
        input.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            if (value.length < 2) {
                suggestionsDiv.style.display = 'none';
                return;
            }
            
            const matches = specializations.filter(spec => 
                spec.toLowerCase().includes(value)
            );
            
            if (matches.length > 0) {
                suggestionsDiv.innerHTML = matches.map(match => 
                    `<div class="suggestion-item p-2 border-bottom cursor-pointer" data-value="${match}">${match}</div>`
                ).join('');
                suggestionsDiv.style.display = 'block';
            } else {
                suggestionsDiv.style.display = 'none';
            }
        });
        
        // Handle suggestion selection
        suggestionsDiv.addEventListener('click', function(e) {
            if (e.target.classList.contains('suggestion-item')) {
                input.value = e.target.dataset.value;
                suggestionsDiv.style.display = 'none';
            }
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });
        
        // Handle keyboard navigation
        input.addEventListener('keydown', function(e) {
            const suggestions = suggestionsDiv.querySelectorAll('.suggestion-item');
            const current = suggestionsDiv.querySelector('.suggestion-item.highlighted');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (current) {
                    current.classList.remove('highlighted');
                    const next = current.nextElementSibling;
                    if (next) {
                        next.classList.add('highlighted');
                    } else {
                        suggestions[0].classList.add('highlighted');
                    }
                } else if (suggestions.length > 0) {
                    suggestions[0].classList.add('highlighted');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (current) {
                    current.classList.remove('highlighted');
                    const prev = current.previousElementSibling;
                    if (prev) {
                        prev.classList.add('highlighted');
                    } else {
                        suggestions[suggestions.length - 1].classList.add('highlighted');
                    }
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (current) {
                    input.value = current.dataset.value;
                    suggestionsDiv.style.display = 'none';
                }
            } else if (e.key === 'Escape') {
                suggestionsDiv.style.display = 'none';
            }
        });
    }
    
    // Initialize autocomplete for existing inputs
    document.querySelectorAll('.specialization-input-field').forEach(setupAutocomplete);
    
    // Initialize
    updateRemoveButtons();
});
</script>

<style>
.suggestion-item:hover,
.suggestion-item.highlighted {
    background-color: #f8f9fa;
    cursor: pointer;
}

.suggestion-item:last-child {
    border-bottom: none !important;
}
</style>
@endsection
