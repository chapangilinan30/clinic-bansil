<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Clinica Bansil</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karma:wght@300;400;500;600;700&family=Montserrat+Alternates:wght@500;600;700&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .brand-sidebar-teal { background-color: #1F6F8B; }
        .brand-text-teal { color: #1F6F8B; }
        .brand-header-bg { background-color: #CBDCEB; }
        .brand-header-text { color: #003366; }
        .brand-subtext-gray { color: #7794a3; }
        .font-karma { font-family: 'Karma', serif; }
        .font-montserrat { font-family: 'Montserrat Alternates', sans-serif; }
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
    selectedAppointment: null,
    selectedDoctor: 'N/A'
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

    {{-- ================= SIDEBAR (Added x-cloak to prevent page-load flash) ================= --}}
    <aside x-cloak
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-[#1F6F8B] text-white flex flex-col justify-between p-6 shadow-xl transition-transform duration-300 ease-in-out md:translate-x-0 md:static shrink-0 h-full">
    
        <div class="space-y-8">
            <!-- Brand Logo Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert" alt="Clinica Bansil Logo">
                    <h1 class="text-xl font-bold tracking-wide font-montserrat">Clinica Bansil</h1>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-white/80 hover:text-white text-2xl font-bold focus:outline-none" aria-label="Close Sidebar">
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
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 bg-white brand-text-teal shadow-md font-semibold">
                    <i class="fa-regular fa-calendar-check text-base"></i>
                    <span>Appointments</span>
                </a>

                <a href="{{ route('patient.history') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-white/10">
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
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        {{-- Header Bar --}}
        <header class="bg-white px-6 md:px-8 py-5 flex justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm font-karma">
            <div class="flex items-center gap-3">
                
                <div>
                    <h1 class="text-xl md:text-[25px] font-bold tracking-wide brand-header-text">Appointments</h1>
                    <p class="text-xs md:text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-0.5">
                        Smart Healthcare System
                    </p>
                </div>
            </div>
            
            
        </header>

        {{-- Scrollable Main Content Zone --}}
        <main class="flex-1 p-4 md:p-8 overflow-y-auto pb-24 md:pb-8">
            <div class="max-w-[1400px] mx-auto space-y-6">

                {{-- Two Column Layout Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start font-karma">
                    
                    {{-- Appointments Table Area --}}
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse text-left">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-200/60">
                                            <th class="p-4 text-[11px] font-bold uppercase tracking-wider brand-subtext-gray">Date</th>
                                            <th class="p-4 text-[11px] font-bold uppercase tracking-wider brand-subtext-gray">Time</th>
                                            <th class="p-4 text-[11px] font-bold uppercase tracking-wider brand-subtext-gray">Doctor</th>
                                            <th class="p-4 text-[11px] font-bold uppercase tracking-wider brand-subtext-gray">Status</th>
                                            <th class="p-4 text-center text-[11px] font-bold uppercase tracking-wider brand-subtext-gray w-32">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($appointments as $appointment)
                                            <tr class="hover:bg-slate-50/60 transition-colors">
                                                <td class="p-4 text-sm font-semibold text-slate-700 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                                </td>
                                                <td class="p-4 text-sm text-slate-500 font-medium whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                </td>
                                                <td class="p-4 text-sm text-[#003366] font-bold whitespace-nowrap">
                                                    Dr. {{ $appointment->doctor->name ?? 'N/A' }}
                                                </td>
                                                <td class="p-4 whitespace-nowrap">
                                                    <span class="inline-block bg-amber-50 text-amber-700 border border-amber-200/60 rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td class="p-4 text-center w-32 whitespace-nowrap">
                                                    <button
                                                        type="button"
                                                        @click="openModal = true; selectedAppointment = '{{ $appointment->id }}'; selectedDoctor = 'Dr. {{ addslashes($appointment->doctor->name ?? 'N/A') }}';"
                                                        class="px-4 py-2 bg-[#1F6F8B] hover:bg-[#18576d] text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-sm hover:shadow active:scale-[0.98]">
                                                        Cancel
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-20 text-slate-400">
                                                    <i class="fa-regular fa-calendar-xmark text-slate-300 text-4xl mb-3 block"></i>
                                                    <p class="text-sm font-medium italic">No upcoming appointments found.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Right Notifications / Info Side Panel --}}
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 space-y-4">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <i class="fa-regular fa-bell text-[#1F6F8B]"></i>
                            <h2 class="text-xs font-bold uppercase tracking-wider brand-header-text">Notifications</h2>
                        </div>
                        <div class="bg-slate-50/80 border border-slate-100 p-3.5 rounded-xl text-xs text-slate-600 space-y-1.5">
                            <p class="leading-relaxed">Keep track of your scheduled clinic visits and status updates here.</p>
                            <span class="text-[10px] brand-subtext-gray block font-semibold">Updated Today</span>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    {{-- ================= CANCELLATION MODAL ================= --}}
    <div x-show="openModal" x-cloak class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-100 font-karma" @click.outside="openModal = false">
            <div class="flex justify-between items-center bg-slate-50 px-6 py-4 border-b border-slate-100">
                <h2 class="text-lg font-bold brand-header-text">Cancel Appointment</h2>
                <button type="button" @click="openModal = false" class="text-slate-400 hover:text-slate-600 text-2xl font-bold">&times;</button>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4 bg-slate-50/60 p-4 rounded-xl border border-slate-100">
                    <div>
                        <label class="block text-[10px] font-bold brand-subtext-gray uppercase mb-0.5">Attending Doctor</label>
                        <p class="text-sm font-bold text-[#003366]" x-text="selectedDoctor"></p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold brand-subtext-gray uppercase mb-0.5">Cancellation Policy</label>
                        <p class="text-xs text-rose-600 font-semibold leading-tight">No refund will be issued after cancellation.</p>
                    </div>
                </div>
                
                <form method="POST" :action="'/patient/appointments/' + selectedAppointment + '/cancel'" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-[10px] font-bold brand-subtext-gray uppercase mb-1">Reason for Cancellation</label>
                        <textarea name="cancel_reason" required rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] resize-none" placeholder="Please explain why you need to cancel..."></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                        <button type="button" @click="openModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition">Close</button>
                        <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-sm">Confirm Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- ================= MOBILE BOTTOM NAVIGATION BAR ================= --}}
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200/80 px-6 py-2.5 flex justify-around items-center z-40 shadow-lg font-karma">
    <a href="{{ route('patient.dashboard') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600">
        <i class="fa-solid fa-chart-pie text-lg"></i>
        <span class="text-[9px] font-bold uppercase tracking-wider">Home</span>
    </a>
    
    <a href="{{ route('patient.appointments') }}" class="flex flex-col items-center gap-0.5 text-[#1F6F8B]">
        <i class="fa-solid fa-calendar-check text-lg"></i>
        <span class="text-[9px] font-bold uppercase tracking-wider">Appts</span>
    </a>

    <a href="{{ route('patient.history') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600">
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

</body>
</html>