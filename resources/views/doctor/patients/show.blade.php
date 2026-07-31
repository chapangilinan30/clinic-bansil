@extends('layouts.doctor')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 font-['Karma']" style="font-family: 'Karma', serif;">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- 🏷️ PAGE HEADER & ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                Patient Record
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                View complete patient information and prescriptive history.
            </p>
        </div>

        <a href="{{ route('doctor.patients.index') }}"
           class="inline-flex items-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 text-xs font-bold rounded-xl transition-all active:scale-95 shadow-sm self-start sm:self-auto">
            Back to Patients
        </a>
    </div>

    <!-- 🩺 PATIENT INFORMATION SECTION -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                Patient Demographics
            </h3>
        </div>

        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-slate-700">
                
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Full Name</span>
                    <p class="font-semibold text-slate-800 text-base">
                        {{ $patient->first_name }} {{ $patient->mi ? $patient->mi.'.' : '' }} {{ $patient->last_name }}
                    </p>
                </div>

                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Birth Date</span>
                    <p class="font-semibold text-slate-800 text-base">
                        {{ \Carbon\Carbon::parse($patient->birth_date)->format('F d, Y') }}
                    </p>
                </div>

                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Gender</span>
                    <p class="font-semibold text-slate-800 text-base">
                        {{ ucfirst($patient->gender) ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Contact Number</span>
                    <p class="font-semibold text-slate-800 text-base font-mono">
                        {{ $patient->contact_number ?? 'N/A' }}
                    </p>
                </div>

                <div class="md:col-span-2 pt-4 border-t border-slate-100">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Address</span>
                    <p class="font-semibold text-slate-800">
                        {{ $patient->address ?? 'None recorded' }}
                    </p>
                </div>

                <div class="md:col-span-2 pt-4 border-t border-slate-100">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Medical History Background</span>
                    <p class="font-semibold text-slate-800 whitespace-pre-line">
                        {{ $patient->medical_history ?? 'None recorded' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- 💊 PRESCRIPTION HISTORY SECTION -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                Prescription Records
            </h3>
            <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-100/50">
                {{ $patient->prescriptions->count() }} Prescription(s)
            </span>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            @forelse($patient->prescriptions->sortByDesc('created_at') as $prescription)
                
                <!-- Individual Prescription Card -->
                <div class="p-5 bg-white border border-slate-100 border-l-4 border-l-emerald-500 rounded-r-xl rounded-l-md hover:border-emerald-100 hover:border-l-emerald-600 transition-all duration-200">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100/60 pb-3 mb-4 gap-2">
                        <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">
                            Prescription ID: <span class="font-mono text-slate-700 bg-slate-100 px-2 py-0.5 rounded">#{{ $prescription->id }}</span>
                        </span>
                        
                        <div class="text-xs text-slate-400 font-medium">
                            {{ $prescription->created_at->format('F d, Y h:i A') }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Diagnosis</span>
                        <p class="text-sm font-semibold text-slate-700">{{ $prescription->diagnosis ?? 'N/A' }}</p>
                    </div>

                    <!-- Cleaned Grid List for Medicines -->
                    <div class="border border-slate-100 rounded-xl overflow-hidden mt-4">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider bg-slate-50/30">
                                    <th class="py-3 px-4">Medicine</th>
                                    <th class="py-3 px-4">Dosage</th>
                                    <th class="py-3 px-4">Frequency</th>
                                    <th class="py-3 px-4">Duration</th>
                                    <th class="py-3 px-4">Instructions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-600">
                                @foreach($prescription->items as $item)
                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                        <td class="py-3 px-4 font-semibold text-slate-800">
                                            {{ $item->medicine->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 px-4">{{ $item->dosage ?? '-' }}</td>
                                        <td class="py-3 px-4">{{ $item->frequency ?? '-' }}</td>
                                        <td class="py-3 px-4">{{ $item->duration ?? '-' }}</td>
                                        <td class="py-3 px-4 italic">{{ $item->instructions ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Next Recommended Appointment Date Badge --}}
                    @if($prescription->next_appointment_date)
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Recommended Follow-up Date:</span>
                            <span class="inline-flex items-center gap-1.5 bg-teal-50 border border-teal-200 text-teal-800 px-3 py-1 rounded-lg text-xs font-bold">
                                <i class="fa-regular fa-calendar-check"></i>
                                {{ \Carbon\Carbon::parse($prescription->next_appointment_date)->format('F d, Y') }}
                            </span>
                        </div>
                    @endif

                </div>

            @empty
                <!-- Empty State (No Icons) -->
                <div class="py-12 text-center border border-dashed border-slate-200 bg-slate-50/30 rounded-xl">
                    <h4 class="text-sm font-semibold text-slate-700">No prescriptions recorded yet</h4>
                    <p class="text-xs text-slate-400 mt-1">There are no existing prescriptions stored in the system archives.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection