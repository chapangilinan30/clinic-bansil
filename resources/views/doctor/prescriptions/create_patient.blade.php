@extends('layouts.doctor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 font-['Karma']" style="font-family: 'Karma', serif;">

    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
        <div>
            <h2 class="text-2xl font-bold text-[#003366] tracking-tight">
                Create Prescription
            </h2>
            <p class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider mt-1.5">
                Patient: <span class="text-[#003366] font-bold font-mono">{{ $patient->first_name }} {{ $patient->last_name }}</span>
            </p>
        </div>

        <a href="{{ route('doctor.patients.show', $patient) }}" 
           class="inline-flex items-center bg-[#CBDCEB]/40 hover:bg-[#CBDCEB]/80 text-[#003366] border border-[#003366]/10 px-5 py-2.5 text-xs font-bold rounded-xl transition-all active:scale-95 shadow-sm self-start sm:self-auto">
            Back to Profile
        </a>
    </div>

    <!-- Validation Error Alert -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm space-y-1">
            <p class="font-bold">Please check the form for errors:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('doctor.prescriptions.store') }}" class="space-y-6">
        @csrf

        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        <input type="hidden" name="appointment_id" value="{{ $appointment->id ?? '' }}">

        <!-- Section 1: Clinical Diagnosis & Follow-up -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-[#f4f7fc]">
                <h3 class="text-xs font-bold text-[#0B2D72] uppercase tracking-wider">
                    Clinical Diagnosis & Follow-up
                </h3>
            </div>
            <div class="p-6 md:p-8 space-y-4">
                <div>
                    <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">
                        Primary Diagnosis / Reason for Prescription
                    </label>
                    <textarea name="diagnosis"
                              class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-[#003366] placeholder-slate-400 focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all bg-slate-50/30 font-sans"
                              rows="3"
                              placeholder="Enter patient symptoms, main diagnosis, or relevant clinical notes..."
                              required>{{ old('diagnosis') }}</textarea>
                </div>

                <div>
                    <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">
                        Next Appointment Date (Optional)
                    </label>
                    <input type="date" 
                           name="next_appointment_date" 
                           value="{{ old('next_appointment_date') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all bg-slate-50/30 font-sans">
                </div>
            </div>
        </div>

        <!-- Section 2: Prescribed Medications -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-[#f4f7fc] flex items-center justify-between">
                <h3 class="text-xs font-bold text-[#0B2D72] uppercase tracking-wider">
                    Prescribed Medications
                </h3>
            </div>

            <div class="p-6 md:p-8 space-y-6">
                
                <div id="medicine-wrapper" class="space-y-6">

                    @php
                        $oldMedicines = old('medicine_id', ['']);
                    @endphp

                    @foreach($oldMedicines as $index => $oldMed)
                        <div class="medicine-row border border-slate-100 p-5 md:p-6 rounded-xl bg-slate-50/30 relative transition-all duration-200">
                            
                            <div class="flex items-center justify-between border-b border-slate-100/80 pb-4 mb-5">
                                <span class="row-number text-xs font-black text-[#005596] uppercase tracking-wider bg-[#CBDCEB]/60 px-3 py-1 rounded-lg border border-[#005596]/10">
                                    Medication #{{ $index + 1 }}
                                </span>
                                <button type="button"
                                        class="remove-btn text-xs font-bold text-slate-400 hover:text-red-500 uppercase tracking-wider transition-colors {{ count($oldMedicines) === 1 ? 'hidden' : '' }}">
                                    Remove Item
                                </button>
                            </div>

                            <!-- Medicine Selection -->
                            <div class="mb-5">
                                <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Medicine Name</label>
                                <select name="medicine_id[]" class="medicine-select w-full" required>
                                    <option value="">Search Medicine...</option>
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->id }}" {{ $oldMed == $medicine->id ? 'selected' : '' }}>
                                            {{ $medicine->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Dosage & Instructions Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 font-sans">
                                
                                <!-- Dosage -->
                                <div>
                                    <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider font-['Karma']">Dosage</label>
                                    <select name="dosage[]" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none bg-white transition-all" required>
                                        <option value="">Select Dosage</option>
                                        @foreach(['1 tablet', '2 tablets', '1 capsule', '2 capsules', '5 mL', '10 mL', '1 puff', '2 puffs'] as $dosageOption)
                                            <option value="{{ $dosageOption }}" {{ old('dosage.'.$index) == $dosageOption ? 'selected' : '' }}>
                                                {{ $dosageOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Frequency -->
                                <div>
                                    <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider font-['Karma']">Frequency</label>
                                    <select name="frequency[]" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none bg-white transition-all" required>
                                        <option value="">Select Frequency</option>
                                        @foreach(['Every 4 hours', 'Every 6 hours', 'Every 8 hours', 'Every 12 hours', 'Once a day', 'Twice a day', 'Three times a day', 'Before meals', 'After meals'] as $freqOption)
                                            <option value="{{ $freqOption }}" {{ old('frequency.'.$index) == $freqOption ? 'selected' : '' }}>
                                                {{ $freqOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider font-['Karma']">Duration</label>
                                    <select name="duration[]" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none bg-white transition-all" required>
                                        <option value="">Select Duration</option>
                                        @foreach(['3 days', '5 days', '1 week', '2 weeks', '1 month', 'Maintenance'] as $durOption)
                                            <option value="{{ $durOption }}" {{ old('duration.'.$index) == $durOption ? 'selected' : '' }}>
                                                {{ $durOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Special Instructions -->
                                <div>
                                    <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider font-['Karma']">Special Instructions</label>
                                    <input type="text" 
                                           name="instructions[]" 
                                           value="{{ old('instructions.'.$index) }}"
                                           placeholder="e.g., Take with a full glass of water"
                                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none bg-white transition-all">
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="pt-2">
                    <button type="button" id="add-medicine" 
                            class="w-full border-2 border-dashed border-[#0992C2]/40 text-[#0992C2] hover:border-[#0992C2] hover:text-[#003366] hover:bg-[#0992C2]/5 font-bold text-xs uppercase tracking-wider py-4 rounded-xl transition-all duration-200">
                        + Add Another Medicine
                    </button>
                </div>

            </div>

            <div class="px-6 py-5 bg-[#f4f7fc] border-t border-slate-100 flex items-center justify-end">
                <button type="submit" 
                        class="bg-[#003366] text-white px-8 py-3 rounded-xl hover:bg-[#0B2D72] transition-all font-bold text-sm shadow-md active:scale-95">
                    Save & Submit Prescription
                </button>
            </div>
        </div>

    </form>
</div>

<!-- Scripts & Select2 Styles -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container--default .select2-selection--single {
        height: 46px;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        background-color: #ffffff;
        transition: all 0.20s ease-in-out;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #003366;
        font-size: 0.875rem;
        font-weight: 500;
        padding-left: 1rem;
        font-family: sans-serif;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
        top: 1px;
        right: 10px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #0992C2;
        box-shadow: 0 0 0 4px rgba(9, 146, 194, 0.15);
        outline: none;
    }
</style>

<script>
function initializeSelect2OnElement(element) {
    $(element).find('.medicine-select').each(function() {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
        }
        $(this).select2({
            placeholder: "Search Medicine...",
            width: '100%',
            allowClear: true
        });
    });
}

function updateRowNumbers() {
    $('.medicine-row').each(function(index) {
        $(this).find('.row-number').text('Medication #' + (index + 1));
    });
    
    // Toggle remove button visibility based on row count
    if ($('.medicine-row').length === 1) {
        $('.medicine-row .remove-btn').addClass('hidden');
    } else {
        $('.medicine-row .remove-btn').removeClass('hidden');
    }
}

$(document).ready(function () {
    // Initial load
    initializeSelect2OnElement('#medicine-wrapper');

    $('#add-medicine').click(function () {
        let firstRow = $('.medicine-row:first');
        
        // Safely destroy Select2 on cloned element before cloning to prevent duplicate IDs
        firstRow.find('.medicine-select').select2('destroy');
        
        let newRow = firstRow.clone(true);

        // Re-initialize original Select2
        firstRow.find('.medicine-select').select2({
            placeholder: "Search Medicine...",
            width: '100%',
            allowClear: true
        });

        // Clean values in cloned row
        newRow.find('input').val('');
        newRow.find('select').val('');

        // Append and re-initialize Select2 on new row
        $('#medicine-wrapper').append(newRow);
        initializeSelect2OnElement(newRow);
        
        updateRowNumbers();
    });

    $(document).on('click', '.remove-btn', function () {
        if ($('.medicine-row').length > 1) {
            $(this).closest('.medicine-row').remove();
            updateRowNumbers();
        }
    });
});
</script>
@endsection