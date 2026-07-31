<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit History - Clinica Bansil</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karma:wght@300;400;500;600;700&family=Montserrat+Alternates:wght@500;600;700&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .brand-sidebar-teal { background-color: #1F6F8B; }
        .brand-text-teal { color: #1F6F8B; }
        .brand-header-bg { background-color: #CBDCEB; }
        .brand-header-text { color: #003366; }
        .brand-subtext-gray { color: #7794a3; }
        .font-karma { font-family: 'Karma', serif; }
        .font-montserrat { font-family: 'Montserrat Alternates', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f4f7fc] text-slate-700 h-screen flex flex-col overflow-hidden font-sans" x-data="{ sidebarOpen: false }">

@php
    $userName = auth()->user()->name ?? 'Guest User';
    $nameParts = explode(' ', trim($userName));
    $initials = count($nameParts) >= 2 
        ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
        : strtoupper(substr($userName, 0, 2));
@endphp

<div x-data="{ 
    openModal: false, 
    selectedVisit: { 
        date: '', 
        time: '', 
        doctor: '', 
        reason: '', 
        cancelReason: '', 
        status: '', 
        diagnosis: '', 
        medicines: [],
        nextAppointmentDate: ''
    } 
}" class="flex-1 flex overflow-hidden relative">

    {{-- ================= COLLAPSIBLE MOBILE SIDEBAR BACKDROP ================= --}}
    <div x-show="sidebarOpen" 
         x-cloak
         @click="sidebarOpen = false" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden">
    </div>

    {{-- ================= SIDEBAR ================= --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           x-cloak
           class="fixed inset-y-0 left-0 z-50 w-64 bg-[#1F6F8B] text-white flex flex-col justify-between p-6 shadow-xl transition-transform duration-300 ease-in-out md:translate-x-0 md:static shrink-0 h-full">
        
        <div class="space-y-8">
            <!-- Brand Logo Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert" alt="Logo">
                    <h1 class="text-xl font-bold tracking-wide font-montserrat">Clinica Bansil</h1>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-white/80 hover:text-white text-2xl font-bold focus:outline-none">
                    &times;
                </button>
            </div>

            {{-- User Profile Card --}}
            <div class="bg-white/10 p-4 rounded-xl flex flex-col items-center text-center shadow-inner backdrop-blur-sm">
                <div class="w-16 h-16 rounded-full bg-white border-2 border-white/40 flex items-center justify-center brand-text-teal text-2xl font-bold mb-3 shadow">
                    {{ $initials }}
                </div>
                <h2 class="text-sm font-semibold tracking-wide truncate w-full">
                    {{ $userName }}
                </h2>
                <p class="text-[11px] text-blue-100 uppercase tracking-widest mt-1 opacity-90">
                    Patient Account
                </p>
                
            </div>

            {{-- Navigation Links --}}
            <nav class="space-y-1.5 text-sm font-medium font-karma">
                <a href="{{ route('patient.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-white/10">
                    <i class="fa-solid fa-chart-pie text-base"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('patient.appointments') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-white/10">
                    <i class="fa-regular fa-calendar-check text-base"></i>
                    <span>Appointments</span>
                </a>

                <a href="{{ route('patient.history') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 bg-white brand-text-teal shadow-md font-semibold">
                    <i class="fa-solid fa-clock-rotate-left text-base"></i>
                    <span>History</span>
                </a>
            </nav>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit"
                    class="flex items-center justify-center gap-2 hover:bg-[#3C7284] text-white px-4 py-3 rounded-xl font-bold tracking-wide transition w-full shadow-md">
                <i class="fa-solid fa-sign-out-alt"></i>
                Sign Out
            </button>
        </form>
    </aside>

    {{-- ================= MAIN CONTENT AREA ================= --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto pb-20 md:pb-0">

        {{-- Desktop Header Bar --}}
        <header class="hidden md:flex bg-white px-8 py-5 justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm font-karma">
            <div>
                <h1 class="text-[25px] font-bold tracking-wide brand-header-text">Visit History</h1>
                <p class="text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-0.5">
                    Smart Healthcare System
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-sm brand-subtext-gray brand-header-text font-bold opacity-90">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                </div>
            </div>
        </header>
        
        {{-- Mobile Top Header Bar matching reference layout --}}
        <div class="md:hidden bg-white px-6 py-4 flex items-center justify-between border-b border-slate-100 shadow-sm sticky top-0 z-30">
            <div>
                <h1 class="text-xl font-bold font-karma text-[#003366] tracking-tight">Visit History</h1>
                <p class="text-[10px] font-bold text-[#7794a3] uppercase tracking-widest">Smart Healthcare System</p>
            </div>
            
        </div>

        <main class="flex-1 p-4 md:p-8 space-y-6 max-w-6xl w-full mx-auto">

            {{-- Visit History List Container structured to match the reference card aesthetic --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
                
                {{-- 1. Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="border-b border-slate-100 text-[#7794a3]">
                                <th class="p-5 text-[11px] font-bold uppercase tracking-widest">Date</th>
                                <th class="p-5 text-[11px] font-bold uppercase tracking-widest">Doctor</th>
                                <th class="p-5 text-[11px] font-bold uppercase tracking-widest">Reason</th>
                                <th class="p-5 text-[11px] font-bold uppercase tracking-widest">Diagnosis / Update</th>
                                <th class="p-5 text-[11px] font-bold uppercase tracking-widest">Status</th>
                                <th class="p-5 text-center text-[11px] font-bold uppercase tracking-widest w-28">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($history as $visit)
                                @php
                                    $payload = [
                                        'date' => \Carbon\Carbon::parse($visit->appointment_date)->format('F d, Y'),
                                        'time' => $visit->appointment_time ? \Carbon\Carbon::parse($visit->appointment_time)->format('h:i A') : 'N/A',
                                        'doctor' => 'Dr. ' . ($visit->doctor->name ?? 'N/A'),
                                        'reason' => $visit->reason ?? '',
                                        'cancelReason' => $visit->cancel_reason ?? '',
                                        'status' => ucfirst(strtolower($visit->status)),
                                        'diagnosis' => ($visit->prescription && $visit->prescription->diagnosis) ? $visit->prescription->diagnosis : 'No diagnosis recorded.',
                                        'medicines' => ($visit->prescription && $visit->prescription->medicines) 
                                            ? $visit->prescription->medicines->map(fn($m) => ['name' => $m->name, 'details' => $m->details])->toArray() 
                                            : [],
                                        'nextAppointmentDate' => ($visit->prescription && $visit->prescription->next_appointment_date) 
                                            ? \Carbon\Carbon::parse($visit->prescription->next_appointment_date)->format('F d, Y') 
                                            : ''
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="p-5 text-sm font-semibold text-slate-700 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($visit->appointment_date)->format('M d, Y') }}
                                    </td>
                                    <td class="p-5 text-sm font-bold text-[#003366] whitespace-nowrap">
                                        Dr. {{ $visit->doctor->name ?? 'N/A' }}
                                    </td>
                                    <td class="p-5 text-sm text-slate-500 max-w-xs truncate">
                                        {{ $visit->reason }}
                                    </td>
                                    <td class="p-5 text-sm max-w-xs truncate">
                                        @if(strtolower($visit->status) === 'cancelled')
                                            <span class="text-rose-500 font-medium italic">
                                                Cancelled: {{ $visit->cancel_reason ?? 'No reason provided' }}
                                            </span>
                                        @elseif($visit->prescription && $visit->prescription->diagnosis)
                                            <span class="text-slate-700 font-semibold">{{ $visit->prescription->diagnosis }}</span>
                                        @else
                                            <span class="text-slate-400 italic">No diagnosis recorded</span>
                                        @endif
                                    </td>
                                    <td class="p-5 whitespace-nowrap">
                                        @if(strtolower($visit->status) === 'completed')
                                            <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider">
                                                Completed
                                            </span>
                                        @elseif(strtolower($visit->status) === 'cancelled')
                                            <span class="inline-block bg-rose-50 text-rose-700 border border-rose-200 rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="inline-block bg-slate-100 text-slate-600 rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider">
                                                {{ ucfirst($visit->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-5 text-center w-28">
                                        <button
                                            type="button"
                                            @click='selectedVisit = @json($payload); openModal = true'
                                            class="px-4 py-2 bg-[#1F6F8B] hover:bg-[#18586e] text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-sm active:scale-[0.98]">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-20 text-slate-400">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 text-2xl shadow-inner">
                                                <i class="fa-regular fa-calendar-xmark"></i>
                                            </div>
                                            <p class="text-sm font-medium italic text-slate-400">No visit history found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 2. Mobile Card / Table Layout matching the reference image structure --}}
                <div class="block md:hidden">
                    {{-- Column header row mimicking reference appts page headers --}}
                    <div class="grid grid-cols-5 px-6 py-4 border-b border-slate-100 text-[10px] font-bold uppercase tracking-widest text-[#7794a3] text-center">
                        <span class="text-left">Date</span>
                        <span class="text-left">Time</span>
                        <span class="text-left col-span-2">Doctor</span>
                        <span class="text-right">Action</span>
                    </div>

                    @forelse($history as $visit)
                        @php
                            $payload = [
                                'date' => \Carbon\Carbon::parse($visit->appointment_date)->format('F d, Y'),
                                'time' => $visit->appointment_time ? \Carbon\Carbon::parse($visit->appointment_time)->format('h:i A') : 'N/A',
                                'doctor' => 'Dr. ' . ($visit->doctor->name ?? 'N/A'),
                                'reason' => $visit->reason ?? '',
                                'cancelReason' => $visit->cancel_reason ?? '',
                                'status' => ucfirst(strtolower($visit->status)),
                                'diagnosis' => ($visit->prescription && $visit->prescription->diagnosis) ? $visit->prescription->diagnosis : 'No diagnosis recorded.',
                                'medicines' => ($visit->prescription && $visit->prescription->medicines) 
                                    ? $visit->prescription->medicines->map(fn($m) => ['name' => $m->name, 'details' => $m->details])->toArray() 
                                    : [],
                                'nextAppointmentDate' => ($visit->prescription && $visit->prescription->next_appointment_date) 
                                    ? \Carbon\Carbon::parse($visit->prescription->next_appointment_date)->format('F d, Y') 
                                    : ''
                            ];
                        @endphp
                        <div class="grid grid-cols-5 px-6 py-4 items-center border-b border-slate-100 text-xs hover:bg-slate-50/50 transition-colors">
                            <span class="text-slate-700 font-semibold text-left">
                                {{ \Carbon\Carbon::parse($visit->appointment_date)->format('M d') }}
                            </span>
                            <span class="text-slate-500 text-left">
                                {{ $visit->appointment_time ? \Carbon\Carbon::parse($visit->appointment_time)->format('h:i A') : 'N/A' }}
                            </span>
                            <span class="text-[#003366] font-bold text-left col-span-2 truncate pr-2">
                                Dr. {{ $visit->doctor->name ?? 'N/A' }}
                            </span>
                            <div class="text-right">
                                <button
                                    type="button"
                                    @click='selectedVisit = @json($payload); openModal = true'
                                    class="px-3 py-1.5 bg-[#1F6F8B] text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition shadow-sm active:scale-[0.98]">
                                    View
                                </button>
                            </div>
                        </div>
                    @empty
                        {{-- Empty state precisely matching reference image --}}
                        <div class="text-center py-24 px-6">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 text-2xl shadow-inner">
                                <i class="fa-regular fa-calendar-xmark"></i>
                            </div>
                            <p class="text-sm font-medium italic text-slate-400">No visit history found.</p>
                        </div>
                    @endforelse
                </div>

            </div>

            {{-- Notifications Section matching the secondary card in the reference image --}}
           

        </main>
    </div>

    {{-- ================= CLINICAL VISIT DETAIL MODAL ================= --}}
    <div
        x-show="openModal"
        x-cloak
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-end sm:items-center justify-center z-50 p-0 sm:p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="bg-white rounded-t-3xl sm:rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col">
            
            <div class="flex justify-between items-center bg-[#CBDCEB]/40 px-6 py-4 border-b border-slate-200/60">
                <h3 class="text-lg font-bold text-[#003366] font-montserrat tracking-tight">
                    Complete Visit Information
                </h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-2xl leading-none focus:outline-none">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto space-y-6">
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date & Time</span>
                        <span class="text-slate-800 text-xs font-bold" x-text="selectedVisit.date + ' at ' + selectedVisit.time"></span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Attending Doctor</span>
                        <span class="text-[#003366] text-xs font-bold" x-text="selectedVisit.doctor"></span>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Appointment Status</span>
                        <span :class="{
                            'bg-emerald-50 text-emerald-700 border-emerald-200': selectedVisit.status === 'Completed',
                            'bg-rose-50 text-rose-700 border-rose-200': selectedVisit.status === 'Cancelled',
                            'bg-slate-100 text-slate-600 border-slate-200': selectedVisit.status !== 'Completed' && selectedVisit.status !== 'Cancelled'
                        }" class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg border mt-1" x-text="selectedVisit.status"></span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reason for Visit</span>
                    <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-3 text-slate-700 text-xs font-medium leading-relaxed" x-text="selectedVisit.reason"></div>
                </div>

                <div x-show="selectedVisit.status === 'Cancelled'" class="space-y-1.5">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reason for Cancellation</span>
                    <div class="bg-rose-50 border border-rose-100 rounded-xl p-3 text-rose-900 font-semibold text-xs leading-relaxed" 
                         x-text="selectedVisit.cancelReason || 'No reason was provided.'">
                    </div>
                </div>

                <div x-show="selectedVisit.status !== 'Cancelled'" class="space-y-6">
                    <div class="space-y-1.5">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Doctor's Diagnosis</span>
                        <div class="bg-blue-50/40 border border-blue-100 rounded-xl p-3 text-[#003366] font-bold text-xs leading-relaxed" x-text="selectedVisit.diagnosis"></div>
                    </div>

                    <div class="space-y-2">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Prescribed Medication</span>
                        
                        <div x-show="selectedVisit.medicines && selectedVisit.medicines.length > 0">
                            <div class="bg-slate-50/50 border border-slate-100 rounded-xl divide-y divide-slate-100 overflow-hidden">
                                <template x-for="(med, index) in selectedVisit.medicines" :key="index">
                                    <div class="p-3 text-xs flex items-start justify-between">
                                        <div class="space-y-0.5">
                                            <div class="font-bold text-slate-800" x-text="med.name"></div>
                                            <div class="text-[11px] text-slate-400 font-medium" x-text="med.details" x-show="med.details"></div>
                                        </div>
                                        <span class="bg-[#CBDCEB] text-[#003366] text-[9px] font-bold px-2 py-0.5 rounded uppercase shrink-0">Rx</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="!selectedVisit.medicines || selectedVisit.medicines.length === 0" class="text-slate-400 italic text-xs p-3 bg-slate-50/40 border border-slate-100 border-dashed rounded-xl text-center">
                            No medications were prescribed during this visit.
                        </div>
                    </div>

                    {{-- Next Recommended Consultation Date --}}
                    <div x-show="selectedVisit.nextAppointmentDate" class="space-y-1.5 pt-2 border-t border-slate-100">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Next Recommended Consultation</span>
                        <div class="bg-teal-50/60 border border-teal-100 rounded-xl p-3 flex items-center gap-2.5 text-[#1F6F8B]">
                            <i class="fa-regular fa-calendar-plus text-base"></i>
                            <span class="text-xs font-bold" x-text="selectedVisit.nextAppointmentDate"></span>
                        </div>
                    </div>
                </div>

            </div>

            

        </div>
    </div>

    {{-- ================= MOBILE BOTTOM NAVIGATION BAR matching reference image styling ================= --}}
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-6 py-2.5 flex justify-around items-center z-40 shadow-lg">
        <a href="{{ route('patient.dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-chart-pie text-lg"></i>
            <span class="text-[9px] font-bold uppercase tracking-wider">Home</span>
        </a>
        
        <a href="{{ route('patient.appointments') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-calendar-check text-lg"></i>
            <span class="text-[9px] font-bold uppercase tracking-wider">Appts</span>
        </a>

        <a href="{{ route('patient.history') }}" class="flex flex-col items-center gap-0.5 brand-text-teal">
            <i class="fa-solid fa-clock-rotate-left text-lg"></i>
            <span class="text-[9px] font-bold uppercase tracking-wider">History</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="flex flex-col items-center gap-0.5 text-rose-500">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[9px] font-bold uppercase tracking-wider">Logout</span>
            </button>
        </form>
    </div>
 
</div>

</body>
</html>