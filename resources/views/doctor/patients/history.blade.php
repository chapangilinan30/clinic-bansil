@extends('layouts.doctor')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 font-['Karma']" style="font-family: 'Karma', serif;">

    {{-- Top Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-[#003366] tracking-tight">
                Diagnosis & Medical History
            </h2>
            <p class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider mt-1.5 flex items-center gap-1.5">
                <span>Patient:</span>
                <span class="text-[#003366] font-bold">{{ $patient->first_name }} {{ $patient->last_name }}</span>
            </p>
        </div>

        <a href="{{ route('doctor.patients.show', $patient) }}" 
           class="inline-flex items-center gap-2 bg-[#CBDCEB]/40 hover:bg-[#1F6F8B] text-[#003366] hover:text-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 border border-[#003366]/10 hover:border-transparent shadow-xs self-start sm:self-auto font-['Karma']">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to Patient Profile
        </a>
    </div>

    {{-- Diagnosis / Notes Section --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-[#f4f7fc]">
            <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-notes-medical text-[#0992C2] text-sm"></i>
                Diagnosis Records
            </h3>
            <span class="bg-[#CBDCEB]/50 text-[#003366] text-[10px] font-bold px-3 py-1 rounded-lg tracking-wider uppercase border border-[#003366]/10">
                {{ $patient->notes->count() }} Record(s)
            </span>
        </div>

        <div class="p-6 md:p-8 space-y-4">
            @if($patient->notes->count())
                @foreach($patient->notes as $note)
                    <div class="p-5 bg-slate-50/40 border border-slate-200/60 border-l-4 border-l-[#0992C2] rounded-r-xl rounded-l-md hover:border-[#0992C2]/40 hover:border-l-[#003366] hover:bg-white hover:shadow-xs transition-all duration-200">
                        <div class="flex items-start justify-between gap-4">
                            <h5 class="font-bold text-[#003366] text-base">
                                {{ $note->diagnosis }}
                            </h5>
                        </div>

                        @if($note->notes)
                            <div class="mt-3 text-sm text-[#003366]/90 leading-relaxed bg-white p-4 rounded-xl border border-slate-200/60 font-sans">
                                {{ $note->notes }}
                            </div>
                        @endif

                        <div class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider mt-4 pt-3 border-t border-slate-100">
                            Added on <span class="text-[#003366] font-bold">{{ $note->created_at->format('F d, Y h:i A') }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="py-16 text-center border-2 border-dashed border-slate-200 bg-slate-50/30 rounded-xl">
                    <div class="w-12 h-12 rounded-2xl bg-[#CBDCEB]/30 border border-[#003366]/10 flex items-center justify-center mx-auto mb-3 text-[#003366]">
                        <i class="fa-solid fa-clipboard-question text-xl"></i>
                    </div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#003366] mb-1.5">
                        No Diagnosis Records Found
                    </h4>
                    <p class="text-xs text-[#7794a3] max-w-sm mx-auto leading-relaxed">
                        There are no clinical diagnosis logs saved for this patient.
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Prescription History Section --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-[#f4f7fc]">
            <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-file-prescription text-[#0992C2] text-sm"></i>
                Prescription History
            </h3>
            <span class="bg-[#CBDCEB]/50 text-[#003366] text-[10px] font-bold px-3 py-1 rounded-lg tracking-wider uppercase border border-[#003366]/10">
                {{ $patient->prescriptions->count() }} Prescription(s)
            </span>
        </div>

        <div class="p-6 md:p-8 space-y-4">
            @if($patient->prescriptions->count())
                @foreach($patient->prescriptions as $prescription)
                    <div class="p-5 bg-slate-50/40 border border-slate-200/60 border-l-4 border-l-[#1F6F8B] rounded-r-xl rounded-l-md hover:border-[#0992C2]/40 hover:border-l-[#0992C2] hover:bg-white hover:shadow-xs transition-all duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3 mb-4">
                            <span class="text-xs font-bold text-[#7794a3] tracking-wider uppercase flex items-center gap-2">
                                Prescription ID: 
                                <span class="font-mono text-[#003366] bg-[#CBDCEB]/40 px-2.5 py-1 rounded-md border border-[#003366]/10 font-bold">
                                    #{{ $prescription->id }}
                                </span>
                            </span>
                            
                            <div class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider">
                                {{ $prescription->created_at->format('F d, Y h:i A') }}
                            </div>
                        </div>

                        {{-- Pill Badges for Prescribed Medicines --}}
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($prescription->medicines as $medicine)
                                <span class="bg-[#f4f7fc] border border-[#CBDCEB] text-[#003366] text-xs font-bold px-3.5 py-1.5 rounded-xl shadow-2xs flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#0992C2]"></span>
                                    {{ $medicine->name }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Next Recommended Appointment Badge --}}
                        @if($prescription->next_appointment_date)
                            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-2 text-xs text-[#1F6F8B]">
                                <i class="fa-regular fa-calendar-plus text-sm"></i>
                                <span class="font-bold uppercase tracking-wider text-[10px] text-slate-400">Next Consultation:</span>
                                <span class="font-bold text-[#003366]">{{ \Carbon\Carbon::parse($prescription->next_appointment_date)->format('F d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="py-16 text-center border-2 border-dashed border-slate-200 bg-slate-50/30 rounded-xl">
                    <div class="w-12 h-12 rounded-2xl bg-[#CBDCEB]/30 border border-[#003366]/10 flex items-center justify-center mx-auto mb-3 text-[#003366]">
                        <i class="fa-solid fa-pills text-xl"></i>
                    </div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#003366] mb-1.5">
                        No Prescription History Found
                    </h4>
                    <p class="text-xs text-[#7794a3] max-w-sm mx-auto leading-relaxed">
                        This patient does not have any recorded prescriptions in the clinic system.
                    </p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection