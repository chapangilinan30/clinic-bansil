@extends('layouts.doctor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 font-['Karma']" style="font-family: 'Karma', serif;">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                Diagnosis Details
            </h2>
            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-1.5">
                Patient: <span class="text-slate-600 font-bold font-mono">{{ $patient->first_name }} {{ $patient->last_name }}</span>
            </p>
        </div>

        <a href="{{ route('doctor.patients.show', $patient) }}" 
           class="inline-flex items-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 text-xs font-bold rounded-xl transition-all active:scale-95 shadow-sm self-start sm:self-auto">
            Back to Patient
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100/50">
                Primary Diagnosis
            </span>
            
            <h3 class="text-xl font-bold text-slate-800 mt-3">
                {{ $note->diagnosis }}
            </h3>
            
            <div class="text-xs text-slate-400 font-medium mt-2">
                Diagnosed on <span class="text-slate-600">{{ $note->created_at->format('F d, Y \a\t h:i A') }}</span>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">
                Clinical Findings & Notes
            </h4>
            
            <div class="bg-slate-50/60 rounded-xl p-5 border border-slate-100 text-slate-700 leading-relaxed text-sm min-h-[150px] flex flex-col justify-between">
                @if($note->notes)
                    <p class="whitespace-pre-line">{{ $note->notes }}</p>
                @else
                    <div class="flex flex-col items-center justify-center text-slate-400 italic h-full py-8 text-center">
                        <span>No additional clinical notes were recorded for this diagnosis.</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
            <span>
                Clinica Bansil Record
            </span>
            <span class="font-mono">ID: #{{ $note->id }}</span>
        </div>

    </div>

</div>
@endsection