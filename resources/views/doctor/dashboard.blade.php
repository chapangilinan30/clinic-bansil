@extends('layouts.doctor')

@section('content')



{{-- Dashboard Statistics Cards --}}
<div id="doctor-queue-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 font-['Karma']" style="font-family: 'Karma', serif;">

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
<div id="doctor-appointment-pipeline" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden font-['Karma']" style="font-family: 'Karma', serif;">
    
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-[#003366] text-lg">Today's Patient Pipeline</h3>
        <!-- Active Appointments Tag -->
        <span class="px-3 py-1 bg-blue-50 text-[#236ff2] border border-blue-200/60 text-xs font-semibold rounded-full">
            Active Appointments
        </span>
    </div>

    <div class="hidden overflow-x-auto md:block">
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
                            <span class="text-xs font-medium text-slate-500">Available when in progress</span>
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

    <div class="space-y-3 p-4 md:hidden">
        @forelse($todaysAppointments as $appointment)
            @php
                $statusClasses = [
                    'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'called' => 'bg-sky-50 text-sky-800 border-sky-200',
                    'checked-in' => 'bg-orange-50 text-orange-800 border-orange-200',
                    'in-progress' => 'bg-blue-50 text-blue-800 border-blue-200',
                    'completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                ];
                $statusClass = $statusClasses[$appointment->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
            @endphp
            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-500">Queue #{{ $appointment->queue_number }} · {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                        <h4 class="mt-1 truncate text-base font-bold text-[#003366]">{{ $appointment->patient_name }}</h4>
                    </div>
                    <span class="shrink-0 rounded-lg border px-2 py-1 text-[10px] font-bold uppercase {{ $statusClass }}">{{ ucfirst($appointment->status) }}</span>
                </div>
                <div class="mt-3 flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
                    <div class="min-w-0 text-xs text-slate-600">
                        <p class="truncate">{{ $appointment->patient_phone ?: 'No contact number' }}</p>
                        <p class="mt-1 truncate text-slate-500">{{ $appointment->purpose ?: 'Consultation' }}</p>
                    </div>
                    @if($appointment->status === 'in-progress')
                        <a href="{{ route('doctor.appointment.show', $appointment->id) }}" class="shrink-0 rounded-lg bg-[#1F6F8B] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#18576d] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]">Consult</a>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 px-5 py-8 text-center text-sm text-slate-600">No appointments scheduled for today.</div>
        @endforelse
    </div>

</div>

<script>
async function refreshDoctorAppointments() {
    if (document.hidden) return;

    try {
        const response = await fetch(window.location.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            cache: 'no-store'
        });
        if (!response.ok) return;

        const refreshedDocument = new DOMParser().parseFromString(await response.text(), 'text/html');
        ['#doctor-queue-stats', '#doctor-appointment-pipeline'].forEach(function (selector) {
            const currentSection = document.querySelector(selector);
            const refreshedSection = refreshedDocument.querySelector(selector);
            if (currentSection && refreshedSection) {
                currentSection.innerHTML = refreshedSection.innerHTML;
            }
        });
    } catch (error) {
        console.error('Doctor appointment refresh failed:', error);
    }
}

setInterval(refreshDoctorAppointments, 5000);
</script>

@endsection