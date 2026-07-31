@extends('layouts.doctor')

@section('content')

<div class="max-w-4xl mx-auto space-y-6 font-['Karma']" style="font-family: 'Karma', serif;">

    {{-- Top Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-[#003366] tracking-tight">
                Consultation Session
            </h2>
            <p class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider mt-1.5 flex items-center gap-2">
                <span>Patient:</span>
                <span class="text-[#003366] font-bold text-sm capitalize">
                    {{ $appointment->patient_name }}
                </span>
            </p>
        </div>

        <a href="{{ route('doctor.dashboard') }}" 
           class="inline-flex items-center gap-2 bg-[#CBDCEB]/40 hover:bg-[#CBDCEB]/80 text-[#003366] px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 border border-[#003366]/10 shadow-xs self-start sm:self-auto">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to Dashboard
        </a>
    </div>

    <!-- 📌 APPOINTMENT INFORMATION HEADER CARD -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-[#f4f7fc] flex items-center justify-between">
            <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-calendar-check text-[#0992C2] text-sm"></i>
                Appointment Overview
            </h3>
            <span class="bg-[#CBDCEB]/50 text-[#003366] text-[10px] font-bold px-3 py-1 rounded-lg tracking-wider uppercase border border-[#003366]/10">
                Queue #{{ $appointment->queue_number }}
            </span>
        </div>
        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-slate-50/60 p-4 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-bold text-[#7794a3] uppercase tracking-wider block mb-1">Queue Position</span>
                    <p class="text-xl font-bold font-mono text-[#003366]">#{{ $appointment->queue_number }}</p>
                </div>
                <div class="bg-slate-50/60 p-4 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-bold text-[#7794a3] uppercase tracking-wider block mb-1">Scheduled Time</span>
                    <p class="text-sm font-bold text-[#003366] mt-1">{{ $appointment->appointment_time }}</p>
                </div>
                <div class="bg-slate-50/60 p-4 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-bold text-[#7794a3] uppercase tracking-wider block mb-1">Purpose of Visit</span>
                    <p class="text-sm font-bold text-[#003366] mt-1">{{ $appointment->purpose ?? 'General Consultation' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 📝 CONSULTATION & PRESCRIPTION FORM -->
    <form method="POST" action="{{ route('doctor.prescriptions.store_dashboard') }}" class="space-y-6">
        @csrf

        {{-- Hidden appointment ID --}}
        <input type="hidden" name="appointment_id" value="{{ old('appointment_id', $appointment->id) }}">

        <!-- CARD 1: CLINICAL DIAGNOSIS & FOLLOW-UP -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-[#f4f7fc]">
                <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-stethoscope text-[#0992C2] text-sm"></i>
                    Clinical Diagnosis & Follow-up
                </h3>
            </div>
            <div class="p-6 md:p-8 space-y-5">
                <div>
                    <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">
                        Primary Diagnosis / Reason for Prescription
                    </label>
                    <textarea name="diagnosis"
                              class="w-full bg-slate-50/30 border border-slate-200 rounded-xl px-4 py-3 text-sm font-sans text-[#003366] placeholder-slate-400 focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all shadow-xs"
                              rows="3"
                              placeholder="Enter patient symptoms, main diagnosis, or relevant clinical notes..."
                              required>{{ old('diagnosis', $appointment->diagnosis) }}</textarea>
                </div>

                {{-- Next Appointment Date Field --}}
                <div>
                    <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">
                        Next Appointment Date (Optional)
                    </label>
                    <input type="date" 
                           name="next_appointment_date" 
                           value="{{ old('next_appointment_date') }}"
                           class="w-full bg-slate-50/30 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-sans text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all shadow-xs">
                </div>
            </div>
        </div>

        <!-- CARD 2: MEDICATION BUILDER -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-[#f4f7fc] flex items-center justify-between">
                <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-pills text-[#0992C2] text-sm"></i>
                    Prescribed Medications
                </h3>
            </div>

            <div class="p-6 md:p-8 space-y-6">
                
                {{-- Dynamic Medicine Wrapper --}}
                <div id="medicine-wrapper" class="space-y-6">

                    @php
                        $oldMedicines = old('medicine_id', ['']);
                        $oldDosage = old('dosage', []);
                        $oldFrequency = old('frequency', []);
                        $oldDuration = old('duration', []);
                        $oldInstructions = old('instructions', []);
                    @endphp

                    @foreach($oldMedicines as $i => $med)
                    <!-- Single Medicine Row -->
                    <div class="medicine-row border border-slate-200/80 p-5 md:p-6 rounded-xl bg-slate-50/30 relative transition-all duration-200">
                        
                        <!-- Dynamic Header with Number and Remove Button -->
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-3 mb-5">
                            <span class="row-number text-xs font-bold text-[#003366] uppercase tracking-wider bg-[#CBDCEB]/40 px-3 py-1 rounded-lg border border-[#003366]/10">
                                Medication #{{ $i + 1 }}
                            </span>
                            <button type="button"
                                    class="remove-btn text-xs font-bold text-rose-500 hover:text-rose-700 uppercase tracking-wider transition-colors {{ count($oldMedicines) === 1 ? 'hidden' : '' }}">
                                <i class="fa-solid fa-trash-can mr-1"></i> Remove Item
                            </button>
                        </div>

                        {{-- Medicine Selection --}}
                        <div class="mb-5">
                            <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Medicine Name</label>
                            <select name="medicine_id[]" class="medicine-select w-full" required>
                                <option value="">Search Medicine...</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" {{ $medicine->id == $med ? 'selected' : '' }}>
                                        {{ $medicine->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dosage & Instructions Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            {{-- Dosage --}}
                            <div>
                                <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Dosage</label>
                                <select name="dosage[]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-sans text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all" required>
                                    <option value="">Select Dosage</option>
                                    @foreach(['1 tablet','2 tablets','3 tablets','1 capsule','2 capsules','2.5 mL','5 mL','10 mL','1 puff','2 puffs'] as $dos)
                                        <option value="{{ $dos }}" {{ ($oldDosage[$i] ?? '') == $dos ? 'selected' : '' }}>{{ $dos }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Frequency --}}
                            <div>
                                <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Frequency</label>
                                <select name="frequency[]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-sans text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all" required>
                                    <option value="">Select Frequency</option>
                                    @foreach(['Every 4 hours','Every 6 hours','Every 8 hours','Every 12 hours','Once a day','Twice a day','Three times a day','Before meals','After meals'] as $freq)
                                        <option value="{{ $freq }}" {{ ($oldFrequency[$i] ?? '') == $freq ? 'selected' : '' }}>{{ $freq }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Duration --}}
                            <div>
                                <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Duration</label>
                                <select name="duration[]" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-sans text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all" required>
                                    <option value="">Select Duration</option>
                                    @foreach(['3 days','5 days','1 week','2 weeks','1 month','2 months','3 months','Maintenance'] as $dur)
                                        <option value="{{ $dur }}" {{ ($oldDuration[$i] ?? '') == $dur ? 'selected' : '' }}>{{ $dur }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Instructions --}}
                            <div>
                                <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Special Instructions</label>
                                <input type="text" 
                                       name="instructions[]" 
                                       placeholder="e.g., Take after meals"
                                       value="{{ $oldInstructions[$i] ?? '' }}"
                                       class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-sans text-[#003366] placeholder-slate-400 focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all">
                            </div>

                        </div>
                    </div>
                    @endforeach

                </div>

                <!-- Add Button Interface -->
                <div class="pt-2">
                    <button type="button" id="add-medicine" 
                            class="w-full border-2 border-dashed border-slate-200 text-[#003366] hover:border-[#0992C2] hover:bg-[#f4f7fc] font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus text-xs"></i> Add Another Medicine
                    </button>
                </div>

            </div>

            <!-- Form Footer Actions -->
            <div class="px-6 py-4 bg-[#f4f7fc] border-t border-slate-100 flex items-center justify-end">
                <button type="submit" 
                        class="bg-[#003366] hover:bg-[#0992C2] text-white px-8 py-3 rounded-xl transition-all font-bold text-xs uppercase tracking-wider shadow-xs active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Save & Print Prescription
                </button>
            </div>
        </div>

    </form>
</div>

{{-- Scripts & Customized Select2 Styles --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Clinica Bansil Themed Select2 Styles */
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
        font-family: sans-serif;
        padding-left: 1rem;
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
function initSelect2(container) {
    $(container).find('.medicine-select').select2({
        placeholder: "Search Medicine...",
        width: '100%',
        allowClear: true
    });
}

function updateRowNumbers() {
    $('.medicine-row').each(function(index) {
        $(this).find('.row-number').text('Medication #' + (index + 1));
    });
}

$(document).ready(function () {
    // Initial load
    initSelect2(document);

    $('#add-medicine').click(function () {
        let firstRow = $('.medicine-row:first');
        let newRow = firstRow.clone(false);

        // Clean Select2 layout leftovers
        newRow.find('.select2-container').remove();
        newRow.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id');

        // Reset values
        let selectElement = newRow.find('.medicine-select');
        selectElement.removeAttr('data-select2-id')
                     .removeAttr('aria-hidden')
                     .removeAttr('tabindex')
                     .removeClass('select2-hidden-accessible')
                     .val('')
                     .find('option').removeAttr('data-select2-id');

        newRow.find('input').val('');
        newRow.find('select:not(.medicine-select)').val('');

        // Display the remove button
        newRow.find('.remove-btn').removeClass('hidden');

        // Append and initialize Select2
        $('#medicine-wrapper').append(newRow);
        initSelect2(newRow);
        
        // Dynamically update sequence labels
        updateRowNumbers();
    });

    $(document).on('click', '.remove-btn', function () {
        if ($('.medicine-row').length > 1) {
            $(this).closest('.medicine-row').remove();
        }

        if ($('.medicine-row').length === 1) {
            $('.medicine-row .remove-btn').addClass('hidden');
        }

        // Keep labels sequential after removal
        updateRowNumbers();
    });
});
</script>

@endsection