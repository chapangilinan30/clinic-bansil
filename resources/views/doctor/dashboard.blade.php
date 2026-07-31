@extends('layouts.doctor')

@section('content')



{{-- Dashboard Statistics Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 font-['Karma']" style="font-family: 'Karma', serif;">

    <!-- Card 1 -->
    <div class="bg-[#BFDDF0] p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#0B2D72] mb-1">Today's Appointments</p>
            <h3 class="text-3xl font-black text-[#003366]">{{ $totalAppointmentsToday }}</h3>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-[#BFDDF0] p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#0B2D72] mb-1">Patients in Queue</p>
            <h3 class="text-3xl font-black text-[#003366]">{{ $patientsInQueue }}</h3>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-[#BFDDF0] p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#0B2D72] mb-1">Completed Today</p>
            <h3 class="text-3xl font-black text-[#003366]">{{ $completedToday }}</h3>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-[#BFDDF0] p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#0B2D72] mb-1">Ongoing Consults</p>
            <h3 class="text-3xl font-black text-[#003366]">{{ $ongoingConsultations }}</h3>
        </div>
    </div>

</div>

{{-- Modern Today's Appointments Table Container --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden font-['Karma']" style="font-family: 'Karma', serif;">
    
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-[#003366] text-lg">Today's Patient Pipeline</h3>
        <!-- Active Appointments Tag -->
        <span class="px-3 py-1 bg-blue-50 text-[#236ff2] border border-blue-200/60 text-xs font-semibold rounded-full">
            Active Appointments
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-[#7794a3] text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                    <th class="px-6 py-4">Queue</th>
                    <th class="px-6 py-4">Patient</th>
                    <th class="px-6 py-4">Contact No.</th>
                    <th class="px-6 py-4">Appt. Time</th>
                    <th class="px-6 py-4">Reason</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($todaysAppointments as $appointment)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <!-- Queue Number -->
                    <td class="px-6 py-4">
                        <span class="font-black text-[#005596] bg-[#CBDCEB]/50 px-2.5 py-1 rounded-md text-xs">
                            #{{ $appointment->queue_number }}
                        </span>
                    </td>
                    
                    <!-- Patient Name -->
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-700 capitalize">{{ $appointment->patient_name }}</div>
                    </td>

                    <!-- Phone Number -->
                    <td class="px-6 py-4 text-slate-500">
                        {{ $appointment->patient_phone }}
                    </td>

                    <!-- Time -->
                    <td class="px-6 py-4 font-medium text-slate-700">
                        {{ $appointment->appointment_time }}
                    </td>

                    <!-- Purpose -->
                    <td class="px-6 py-4 text-slate-500">
                        {{ $appointment->purpose ?? 'Consultation' }}
                    </td>

                    <!-- Status Pill Badges -->
                    <td class="px-6 py-4">
                        @php
                            $statusClasses = [
                                'pending'     => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                'called'      => 'bg-blue-50 text-[#0992C2] border-blue-200/60',
                                'checked-in'  => 'bg-orange-50 text-orange-600 border-orange-200/60',
                                'in-progress' => 'bg-blue-50 text-[#005596] border-blue-300/60',
                                'completed'   => 'bg-emerald-50 text-emerald-600 border-emerald-200/60',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClasses[$appointment->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </td>

                    <!-- Consult Action -->
                    <td class="px-6 py-4 text-center">
                        @if($appointment->status === 'in-progress')
                            <a href="{{ route('doctor.appointment.show', $appointment->id) }}"
                               class="inline-block px-4 py-1.5 bg-[#0992C2] hover:bg-[#077ca5] text-white font-semibold rounded-lg transition duration-150 text-xs shadow-md">
                                Consult
                            </a>
                        @else
                            <button class="px-4 py-1.5 bg-slate-100 text-slate-400 rounded-lg text-xs font-medium cursor-not-allowed border border-slate-200" disabled>
                                Consult
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-slate-400">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <span class="text-sm font-medium">No appointments scheduled for today</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection