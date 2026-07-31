@extends('layouts.doctor')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 font-['Karma']" style="font-family: 'Karma', serif;">

    <!-- 📌 PAGE HEADER & ACTION BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-[#003366] tracking-tight">
                My Schedules
            </h2>
            <p class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider mt-1.5">
                Manage your weekly availability and specific service dates
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('doctor.schedules.create') }}"
               class="inline-flex items-center gap-2 bg-[#003366] hover:bg-[#0992C2] text-white px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all active:scale-95 shadow-xs">
                <i class="fa-solid fa-plus text-xs"></i>
                Add Schedule
            </a>

            <a href="{{ route('doctor.schedules.show-cancel', $schedules->first()?->id ?? 18) }}" 
               class="inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs uppercase tracking-wider px-5 py-2.5 rounded-xl border border-rose-200/60 transition-all active:scale-95 shadow-xs">
                <i class="fa-solid fa-calendar-xmark text-xs"></i>
                Cancel Appointment
            </a>
        </div>
    </div>

    <!-- 🔔 ALERT BANNER -->
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200/80 text-emerald-800 px-5 py-4 rounded-xl shadow-xs transition-all duration-200">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base flex-shrink-0"></i>
            <span class="text-xs font-bold uppercase tracking-wider">{{ session('success') }}</span>
        </div>
    @endif

    <!-- 📊 MAIN SCHEDULE TABLE CONTAINER -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        
        <div class="px-6 py-4 border-b border-slate-100 bg-[#f4f7fc] flex items-center justify-between">
            <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-[#0992C2] text-sm"></i>
                Configured Schedules
            </h3>
            <span class="bg-[#CBDCEB]/50 text-[#003366] text-[10px] font-bold px-3 py-1 rounded-lg tracking-wider uppercase border border-[#003366]/10">
                Total: {{ $schedules->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[#7794a3] text-[10px] font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-4 px-6">Schedule Type</th>
                        <th class="py-4 px-6">Day / Date</th>
                        <th class="py-4 px-6">Time Range</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-slate-50/60 transition-colors duration-150">
                            
                            <!-- Type Badge -->
                            <td class="py-4 px-6">
                                @if($schedule->day)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-[#CBDCEB]/40 text-[#003366] border border-[#003366]/10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#0992C2]"></span>
                                        Weekly Recurring
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        Specific Date
                                    </span>
                                @endif
                            </td>

                            <!-- Day / Date Display -->
                            <td class="py-4 px-6">
                                <span class="text-xs font-bold text-[#003366] uppercase tracking-wider">
                                    {{ $schedule->day ?? \Carbon\Carbon::parse($schedule->date)->format('F d, Y') }}
                                </span>
                            </td>

                            <!-- Time Formatting -->
                            <td class="py-4 px-6">
                                <div class="inline-flex items-center gap-2 text-xs font-bold text-[#003366] bg-slate-100/70 px-3.5 py-1.5 rounded-xl border border-slate-200/50">
                                    <i class="fa-regular fa-clock text-[#0992C2] text-xs"></i>
                                    <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</span> 
                                    <span class="text-[#7794a3] font-normal">&mdash;</span> 
                                    <span>{{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State Illustration -->
                        <tr>
                            <td colspan="3" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-12 h-12 bg-[#CBDCEB]/30 rounded-2xl flex items-center justify-center mb-3 border border-[#003366]/10">
                                        <i class="fa-solid fa-calendar-xmark text-xl text-[#003366]"></i>
                                    </div>
                                    <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider">No schedules configured</h3>
                                    <p class="text-xs text-[#7794a3] mt-1 mb-5 text-center leading-relaxed">
                                        Get started by setting up your recurring workdays or choosing specific dates of service.
                                    </p>
                                    <a href="{{ route('doctor.schedules.create') }}" 
                                       class="inline-flex items-center gap-2 bg-[#003366] hover:bg-[#0992C2] text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all shadow-xs active:scale-95">
                                        <i class="fa-solid fa-plus text-xs"></i> Set Your Availability
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection