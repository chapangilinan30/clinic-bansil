@extends('layouts.doctor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 font-['Karma']" style="font-family: 'Karma', serif;">

    <!-- Top Header & Actions Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
        <div>
            <h2 class="text-2xl font-bold text-[#003366] tracking-tight">
                Create Prescription
            </h2>
            <p class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider mt-1.5 flex items-center gap-1.5">
                <span>Selected Patient:</span>
                <span class="text-[#003366] font-bold font-mono text-sm bg-[#CBDCEB]/30 px-2 py-0.5 rounded-md border border-[#003366]/10">
                    {{ $patient->first_name }} {{ $patient->last_name }}
                </span>
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <!-- Step Indicator -->
            <div class="hidden md:flex items-center gap-2 bg-[#f4f7fc] border border-[#CBDCEB]/60 px-3.5 py-1.5 rounded-xl shadow-xs">
                <span class="flex h-4 w-4 items-center justify-center rounded-full bg-[#CBDCEB] text-[#003366] text-[9px] font-bold">1</span>
                <span class="text-[11px] font-bold text-[#7794a3] uppercase tracking-wider">Patient</span>
                <span class="text-[#7794a3] text-xs font-bold">&rarr;</span>
                <span class="flex h-4 w-4 items-center justify-center rounded-full bg-[#003366] text-white text-[9px] font-bold shadow-xs">2</span>
                <span class="text-[11px] font-bold text-[#003366] uppercase tracking-wider">Details</span>
            </div>

            <!-- Change Patient Button -->
            <a href="{{ route('doctor.prescriptions.create') }}" 
               class="inline-flex items-center gap-2 bg-[#f4f7fc] hover:bg-[#CBDCEB]/40 text-[#003366] border border-[#CBDCEB]/80 px-4 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 active:scale-95 shadow-xs uppercase tracking-wider">
                <i class="fa-solid fa-user-pen text-xs text-[#0992C2]"></i>
                <span>Change Patient</span>
            </a>
        </div>
    </div>

    <!-- Main Form Wrapper -->
    <form method="POST" action="{{ route('doctor.prescriptions.store') }}" class="space-y-6">
        @csrf

        <input type="hidden" name="patient_id" value="{{ $patient->id }}">

        <!-- CARD 1: CLINICAL DIAGNOSIS & FOLLOW-UP -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-[#f4f7fc] flex items-center gap-2">
                <i class="fa-solid fa-notes-medical text-[#0992C2] text-sm"></i>
                <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider">
                    Clinical Diagnosis & Follow-up
                </h3>
            </div>

            <div class="p-6 md:p-8 space-y-5">
                <!-- Diagnosis Field -->
                <div>
                    <label for="diagnosis" class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">
                        Primary Diagnosis / Reason for Prescription <span class="text-red-500">*</span>
                    </label>
                    <textarea id="diagnosis"
                              name="diagnosis"
                              class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-[#003366] placeholder-slate-400 focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all bg-slate-50/30"
                              rows="3"
                              placeholder="Enter patient symptoms, main diagnosis, or relevant clinical notes..."
                              required></textarea>
                </div>

                <!-- Next Appointment Date -->
                <div class="max-w-xs">
                    <label for="next_appointment_date" class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">
                        Next Appointment Date <span class="text-slate-400 font-normal lowercase">(optional)</span>
                    </label>
                    <input type="date" 
                           id="next_appointment_date"
                           name="next_appointment_date" 
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all bg-slate-50/30">
                </div>
            </div>
        </div>

        <!-- CARD 2: MEDICATION BUILDER -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-[#f4f7fc] flex items-center gap-2">
                <i class="fa-solid fa-pills text-[#0992C2] text-sm"></i>
                <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider">
                    Prescribed Medications
                </h3>
            </div>

            <div class="p-6 md:p-8 space-y-6">
                
                <!-- Dynamic Medicine Wrapper -->
                <div id="medicine-wrapper" class="space-y-6">

                    <!-- Single Medicine Row Block -->
                    <div class="medicine-row border border-slate-200/80 p-5 md:p-6 rounded-2xl bg-slate-50/30 relative transition-all duration-200 shadow-2xs">
                        
                        <!-- Row Header -->
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-4 mb-5">
                            <span class="row-number text-xs font-bold text-[#003366] uppercase tracking-wider bg-[#CBDCEB]/40 px-3 py-1 rounded-lg border border-[#003366]/10">
                                Medication #1
                            </span>
                            <button type="button"
                                    class="remove-btn text-xs font-bold text-[#7794a3] hover:text-red-500 uppercase tracking-wider transition-colors duration-200 flex items-center gap-1.5 hidden cursor-pointer">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                                <span>Remove Item</span>
                            </button>
                        </div>

                        <!-- Medicine Selection Dropdown -->
                        <div class="mb-5">
                            <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">
                                Medicine Name <span class="text-red-500">*</span>
                            </label>
                            <select name="medicine_id[]" class="medicine-select w-full" required>
                                <option value="">Search Medicine...</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dosage & Instructions Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            
                            <!-- Dosage -->
                            <div>
                                <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Dosage <span class="text-red-500">*</span></label>
                                <select name="dosage[]" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none bg-white transition-all" required>
                                    <option value="">Select Dosage</option>
                                    <option value="1 tablet">1 tablet</option>
                                    <option value="2 tablets">2 tablets</option>
                                    <option value="3 tablets">3 tablets</option>
                                    <option value="1 capsule">1 capsule</option>
                                    <option value="2 capsules">2 capsules</option>
                                    <option value="2.5 mL">2.5 mL</option>
                                    <option value="5 mL">5 mL</option>
                                    <option value="10 mL">10 mL</option>
                                    <option value="1 puff">1 puff</option>
                                    <option value="2 puffs">2 puffs</option>
                                </select>
                            </div>

                            <!-- Frequency -->
                            <div>
                                <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Frequency <span class="text-red-500">*</span></label>
                                <select name="frequency[]" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none bg-white transition-all" required>
                                    <option value="">Select Frequency</option>
                                    <option value="Every 4 hours">Every 4 hours</option>
                                    <option value="Every 6 hours">Every 6 hours</option>
                                    <option value="Every 8 hours">Every 8 hours</option>
                                    <option value="Every 12 hours">Every 12 hours</option>
                                    <option value="Once a day">Once a day</option>
                                    <option value="Twice a day">Twice a day</option>
                                    <option value="Three times a day">Three times a day</option>
                                    <option value="Before meals">Before meals</option>
                                    <option value="After meals">After meals</option>
                                </select>
                            </div>

                            <!-- Duration -->
                            <div>
                                <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Duration <span class="text-red-500">*</span></label>
                                <select name="duration[]" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-[#003366] focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none bg-white transition-all" required>
                                    <option value="">Select Duration</option>
                                    <option value="3 days">3 days</option>
                                    <option value="5 days">5 days</option>
                                    <option value="1 week">1 week</option>
                                    <option value="2 weeks">2 weeks</option>
                                    <option value="1 month">1 month</option>
                                    <option value="2 months">2 months</option>
                                    <option value="3 months">3 months</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>

                            <!-- Special Instructions -->
                            <div>
                                <label class="block mb-2 text-xs font-bold text-[#7794a3] uppercase tracking-wider">Instructions</label>
                                <input type="text" name="instructions[]" placeholder="e.g., Take with food"
                                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-[#003366] placeholder-slate-400 focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none bg-white transition-all">
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Add Button Interface -->
                <div class="pt-2">
                    <button type="button" id="add-medicine" 
                            class="w-full border-2 border-dashed border-[#CBDCEB] text-[#003366] hover:border-[#0992C2] hover:bg-[#f4f7fc] font-bold text-xs uppercase tracking-wider py-4 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-circle-plus text-sm text-[#0992C2]"></i>
                        <span>Add Another Medicine</span>
                    </button>
                </div>

            </div>

            <!-- Form Footer Actions -->
            <div class="px-6 py-5 bg-[#f4f7fc] border-t border-slate-100 flex items-center justify-end">
                <button type="submit" 
                        class="bg-[#003366] hover:bg-[#0B2D72] text-white px-8 py-3 rounded-xl transition-all duration-200 font-bold text-xs uppercase tracking-wider shadow-xs active:scale-95 flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Save & Submit Prescription</span>
                </button>
            </div>
        </div>

    </form>
</div>

{{-- External Scripts & Select2 Styles --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Styled Select2 to Match Theme Palette */
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
    .select2-dropdown {
        border-color: #CBDCEB;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: #003366 !important;
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
    // Initialize initial Select2 instance
    initSelect2(document);

    $('#add-medicine').click(function () {
        let firstRow = $('.medicine-row:first');
        let newRow = firstRow.clone(false);

        // Remove Select2 DOM elements created on clone
        newRow.find('.select2-container').remove();
        newRow.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id');

        // Reset cloned form fields
        let selectElement = newRow.find('.medicine-select');
        selectElement.removeAttr('data-select2-id')
                     .removeAttr('aria-hidden')
                     .removeAttr('tabindex')
                     .removeClass('select2-hidden-accessible')
                     .val('')
                     .find('option').removeAttr('data-select2-id');

        newRow.find('input').val('');
        newRow.find('select:not(.medicine-select)').val('');

        // Ensure remove button is visible on cloned items
        newRow.find('.remove-btn').removeClass('hidden');

        // Append to wrapper and initialize Select2 for the new row
        $('#medicine-wrapper').append(newRow);
        initSelect2(newRow);
        
        // Renumber indices
        updateRowNumbers();
    });

    $(document).on('click', '.remove-btn', function () {
        if ($('.medicine-row').length > 1) {
            $(this).closest('.medicine-row').remove();
        }

        if ($('.medicine-row').length === 1) {
            $('.medicine-row .remove-btn').addClass('hidden');
        }

        // Keep numbers updated
        updateRowNumbers();
    });
});
</script>
@endsection