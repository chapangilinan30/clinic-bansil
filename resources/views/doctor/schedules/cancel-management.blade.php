@extends('layouts.doctor')

@section('content')
<div class="container mx-auto px-4 py-4">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Cancellation Management</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-1 bg-white shadow-sm rounded-xl p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold text-gray-800">
                    Select Date: <span class="text-blue-600 font-mono">{{ $startOfMonth->format('F Y') }}</span>
                </h3>
                <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium">Calendar</span>
            </div>

            <div class="grid grid-cols-7 gap-1 text-center text-[11px] font-bold text-gray-400 uppercase border-b pb-1.5 mb-1.5">
                <div class="text-red-400">Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
            </div>

            <div class="grid grid-cols-7 gap-1.5 text-center">
                @for ($i = 0; $i < $blankDaysBefore; $i++)
                    <div class="p-1 bg-gray-50/30 rounded-md"></div>
                @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentLoopDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $carbonLoopDate = \Carbon\Carbon::parse($currentLoopDate);
                        $isToday = $currentLoopDate == date('Y-m-d');
                        $isSelected = $selectedDate == $currentLoopDate;
                        
                        $hasDuty = $doctorSchedules->contains(function($value) use ($currentLoopDate, $carbonLoopDate) {
                            return $value->date == $currentLoopDate || $value->day == $carbonLoopDate->format('l');
                        });
                    @endphp

                    <button type="button" 
                        onclick="selectCalendarDate('{{ $currentLoopDate }}')"
                        class="p-1.5 text-xs font-semibold rounded-lg relative transition flex flex-col items-center justify-center border min-h-[38px] w-full
                            {{ $isSelected ? 'bg-blue-600 text-white border-blue-600 shadow-sm scale-105 font-bold' : 'bg-white text-gray-700 border-gray-200 hover:bg-blue-50/50 hover:border-blue-300' }}
                            {{ $isToday && !$isSelected ? 'border border-blue-500 text-blue-600 font-bold' : '' }}">
                        
                        <span>{{ $day }}</span>

                        @if($hasDuty)
                            <span class="w-1 h-1 rounded-full mt-0.5 {{ $isSelected ? 'bg-white' : 'bg-emerald-500' }}"></span>
                        @endif
                    </button>
                @endfor
            </div>
        </div>

        <div class="lg:col-span-2">
            @if($selectedDate)
                <div class="bg-white shadow-sm rounded-xl p-5 border border-gray-200 border-t-4 border-red-500 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 pb-3 border-b gap-2">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                Affected Patients for: <span class="text-red-600">{{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y (l)') }}</span>
                            </h3>
                            @if($chosenSchedule)
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Time Slot: <span class="font-semibold text-gray-700">{{ date('h:i A', strtotime($chosenSchedule->start_time)) }} – {{ date('h:i A', strtotime($chosenSchedule->end_time)) }}</span>
                                </p>
                            @endif
                        </div>
                        
                        <div>
                            <a href="{{ route('doctor.schedules.index') }}" class="bg-gray-50 hover:bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg transition font-medium text-xs border inline-block">
                                ← Back
                            </a>
                        </div>
                    </div>

                    @if($affectedAppointments && $affectedAppointments->isNotEmpty())
    <form action="{{ route('doctor.schedules.cancel-selected') }}" method="POST">
        @csrf
        
        <div class="overflow-x-auto mb-4 border rounded-lg">
            <table class="w-full text-xs text-left">
                <thead class="bg-red-50 text-red-900 font-semibold border-b">
                    <tr>
                        <th class="p-3 w-12 text-center">Select</th>
                        <th class="p-3">Patient Name</th>
                        <th class="p-3">Queue No.</th>
                        <th class="p-3">Appointment Time</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($affectedAppointments as $appointment)
                        <tr class="hover:bg-gray-50 transition bg-white">
                            <td class="p-3 text-center">
                                <input type="checkbox" name="appointment_ids[]" value="{{ $appointment->id }}" class="rounded border-gray-300 text-red-600 focus:ring-red-500 w-4 h-4">
                            </td>
                            <td class="p-3 font-medium text-gray-900">
                                {{ $appointment->patient_name ?? ($appointment->user->name ?? 'Unknown') }}
                            </td>
                            <td class="p-3 font-mono font-bold text-red-600">
                                {{ $appointment->queue_number }}
                            </td>
                            <td class="p-3 text-gray-600 font-semibold font-mono">
                                {{ date('h:i A', strtotime($appointment->appointment_time)) }}
                            </td>
                            <td class="p-3 text-center">
                                <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mb-4">
            <label for="cancellation_reason" class="block text-xs font-bold text-gray-700 uppercase mb-2">
                Reason for Cancellation <span class="text-red-500">*</span>
            </label>
            <textarea required name="reason" id="cancellation_reason" rows="3" class="w-full text-sm rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" placeholder="Please provide the reason..."></textarea>
        </div>

        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg shadow-sm transition text-xs">
            Cancel Selected & Notify Patients
        </button>
    </form>
@else
    <div class="bg-amber-50 border border-amber-100 text-amber-800 p-5 rounded-lg text-center text-sm font-medium">
        ✨ No active patient appointments booked for this date.
    </div>
@endif
                </div>
            @else
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-8 text-center text-blue-700 text-sm">
                    <span class="text-xl mb-1 block">📅</span>
                    <p class="font-medium">Please click on a specific calendar day number on the left panel to load its affected patient queues.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<form id="calendar-filter-form" action="{{ route('doctor.schedules.cancel-panel') }}" method="GET" class="hidden">
    <input type="hidden" name="selected_date" id="hidden_selected_date">
    <input type="hidden" name="month" value="{{ $month }}">
    <input type="hidden" name="year" value="{{ $year }}">
</form>

<script>
    function selectCalendarDate(dateString) {
        document.getElementById('hidden_selected_date').value = dateString;
        document.getElementById('calendar-filter-form').submit();
    }
</script>
@endsection