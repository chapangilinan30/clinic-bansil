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
    $initials = '';
    if (count($nameParts) >= 2) {
        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
    } else {
        $initials = strtoupper(substr($userName, 0, 2));
    }
@endphp

<div class="flex-1 flex overflow-hidden relative">

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

    {{-- ================= COLLAPSIBLE MOBILE & DESKTOP SIDEBAR ================= --}}
    <aside x-cloak
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-[#1F6F8B] text-white flex flex-col justify-between p-6 shadow-xl transition-transform duration-300 ease-in-out md:translate-x-0 md:static shrink-0 h-full">
        
        <div class="space-y-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert" alt="Clinica Bansil Logo">
                    <h1 class="text-xl font-bold tracking-wide font-montserrat">Clinica Bansil</h1>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-white/80 hover:text-white text-2xl font-bold focus:outline-none" aria-label="Close Sidebar">
                    &times;
                </button>
            </div>

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

            <nav class="space-y-1.5 text-sm font-medium font-karma">
                <a href="#" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 bg-white brand-text-teal shadow-md font-semibold">
                    <i class="fa-solid fa-chart-pie text-base"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('patient.appointments') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-white/10">
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

    {{-- ================= CENTER MAIN LAYOUT ================= --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        
        {{-- Header Bar --}}
        <header class="bg-white px-6 md:px-8 py-5 flex justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm font-karma">
            <div>
                <h1 class="text-xl md:text-[25px] font-bold tracking-wide brand-header-text">Patient Dashboard</h1>
                <p class="text-xs md:text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-0.5">
                    Smart Healthcare System
                </p>
            </div>
        </header>

        {{-- Scrollable Main Content Zone --}}
        <main class="flex-1 p-4 md:p-8 overflow-y-auto pb-24 md:pb-8">
            <div class="max-w-[1200px] mx-auto space-y-6">

                {{-- SUSPENSION WARNING --}}
                @if(auth()->user()->is_locked_from_booking)
                    <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5 md:p-6 space-y-4 font-karma">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center text-lg shrink-0 shadow-sm">
                                <i class="fa-solid fa-user-slash"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-rose-800 text-base">Booking Account Suspended</h4>
                                <p class="text-xs text-rose-700/80 font-medium leading-relaxed mt-0.5">
                                    Your access to online scheduling has been temporarily locked because you accumulated 3 or more unattended cancellations or no-show markers.
                                </p>
                            </div>
                        </div>
                        
                        <hr class="border-rose-100/70">

                        @if(auth()->user()->reactivation_status === 'pending')
                            <div class="bg-amber-50 border border-amber-100 text-amber-800 p-4 rounded-xl flex items-center gap-3 text-xs font-semibold">
                                <i class="fa-solid fa-circle-notch animate-spin text-sm text-amber-500"></i>
                                <span>Appeal Under Review: Your account reactivation request has been submitted and is currently pending audit by desk operators. Please hold.</span>
                            </div>
                        @else
                            <form action="{{ route('patient.reactivation.submit') }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label for="reactivation_reason" class="block font-bold text-slate-700 text-xs uppercase tracking-wider mb-2">
                                        File Reactivation Appeal
                                    </label>
                                    <textarea 
                                        name="reactivation_reason" 
                                        id="reactivation_reason" 
                                        rows="3" 
                                        placeholder="Please detail a valid explanation stating why your scheduling features should be restored..." 
                                        class="w-full text-slate-700 text-xs font-medium border border-slate-200 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition shadow-sm resize-none"
                                        required></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white py-2.5 px-5 rounded-xl shadow-sm hover:shadow font-bold text-xs uppercase tracking-wider transition active:scale-[0.98]">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        Submit Reactivation Request
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endif

                {{-- MOBILE QUICK BOOKING BUTTON --}}
                <div class="block lg:hidden font-karma">
                    @if(auth()->user()->is_locked_from_booking)
                        <div class="bg-white border border-slate-200/80 rounded-xl py-3.5 px-4 text-center select-none shadow-sm">
                            <p class="text-[11px] font-bold text-rose-500 uppercase tracking-wide flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-ban"></i> Booking Disabled due to Suspension
                            </p>
                        </div>
                    @elseif(isset($hasActiveAppointment) && $hasActiveAppointment)
                        <div class="bg-white border border-slate-200/80 rounded-xl py-3.5 px-4 text-center select-none shadow-sm">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wide flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-lock text-slate-400"></i> Booking Locked
                            </p>
                        </div>
                    @else
                        <a href="{{ route('patient.booking.step1') }}"
                           class="flex items-center justify-center gap-2 bg-[#1F6F8B] hover:bg-[#18576d] text-white py-3.5 rounded-xl shadow-sm font-bold text-xs uppercase tracking-wider transition active:scale-[0.98]">
                            <i class="fa-solid fa-circle-plus text-sm"></i> 
                            Book New Appointment
                        </a>
                    @endif
                </div>

                {{-- ACTIVE APPOINTMENT CARD --}}
                @if(isset($activeAppointment) && $activeAppointment)
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-[#1F6F8B] border-y border-r border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 font-karma">
                    <div class="space-y-1.5">
                        <span class="inline-block bg-blue-50 text-[#1F6F8B] text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">
                            Active Appointment Detail
                        </span>
                        <p class="text-sm text-slate-700 font-semibold">
                            Assigned Provider: <span class="font-bold text-[#003366]">{{ $activeAppointment->doctor->name ?? 'N/A' }}</span>
                        </p>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500 font-medium">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[#1F6F8B]"></i> {{ \Carbon\Carbon::parse($activeAppointment->appointment_date)->format('M d, Y') }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-clock text-[#1F6F8B]"></i> {{ \Carbon\Carbon::parse($activeAppointment->appointment_time)->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="w-full sm:w-auto bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg px-4 py-2 text-center sm:text-right">
                        <span class="block text-[9px] uppercase tracking-wider font-bold opacity-75">Status</span>
                        <span class="text-xs font-bold uppercase tracking-widest">{{ $activeAppointment->status }}</span>
                    </div>
                </div>
                @endif

                {{-- LIVE QUEUE MONITOR & PERSONAL TICKET INTEGRATION --}}
                @php
    // Fetch active queue items dynamically
    $nowServingItem = isset($queue) ? $queue->first(fn($i) => in_array(strtolower($i->status), ['in-progress', 'called', 'serving'])) : null;
    
    // Display the queue number or fallback to patient name/number correctly
    $nowServingNumber = $nowServingItem ? ($nowServingItem->queue_number ?? $nowServingItem->patient->name ?? 'N/A') : ($queueData['now_serving_number'] ?? '--');
    $nowServingDoctor = $nowServingItem ? ($nowServingItem->doctor->name ?? $nowServingItem->doctor_name ?? 'N/A') : 'None';

    $nextItem = isset($queue) ? $queue->first(fn($i) => in_array(strtolower($i->status), ['checked-in', 'pending', 'booked'])) : null;
    $nextNumber = $nextItem ? $nextItem->queue_number : ($queueData['next_number'] ?? '--');
    $nextDoctor = $nextItem ? ($nextItem->doctor->name ?? $nextItem->doctor_name ?? 'N/A') : 'None';

    $waitingCount = isset($queue) ? $queue->filter(fn($i) => in_array(strtolower($i->status), ['checked-in', 'pending', 'booked']))->count() : ($queueData['ahead'] ?? 0);

    $userQueueTicket = isset($queue) ? $queue->first(fn($i) => $i->user_id === auth()->id() && !in_array(strtolower($i->status), ['completed', 'cancelled', 'no_show', 'noshow'])) : null;
    $userTicketNumber = $userQueueTicket ? $userQueueTicket->queue_number : (isset($activeAppointment) && $activeAppointment->queue_number ? $activeAppointment->queue_number : '--');
    $userQueueStatus = $userQueueTicket ? strtoupper($userQueueTicket->status) : (isset($activeAppointment) ? strtoupper($activeAppointment->status) : 'NO TICKET');
@endphp

                <div id="queue-box" class="bg-white rounded-2xl p-5 sm:p-6 shadow-md border border-slate-200/60 font-karma mb-4 md:mb-6" x-data="{ monitorTab: 'all' }">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5 border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                            <h2 class="text-xs font-bold uppercase tracking-widest text-[#003366]">
                                Live Queue Monitor
                            </h2>
                        </div>

                        <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-semibold">
                            <button @click="monitorTab = 'all'" 
                                    :class="monitorTab === 'all' ? 'bg-white text-[#003366] shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                    class="px-3 py-1.5 rounded-lg transition-all duration-150 uppercase tracking-wider text-[10px]">
                                Overview
                            </button>
                            <button @click="monitorTab = 'ticket'" 
                                    :class="monitorTab === 'ticket' ? 'bg-white text-[#003366] shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                    class="px-3 py-1.5 rounded-lg transition-all duration-150 uppercase tracking-wider text-[10px]">
                                My Ticket
                            </button>
                        </div>
                    </div>

                    {{-- TAB 1: OVERVIEW --}}
                    <div x-show="monitorTab === 'all'" x-transition.opacity class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        {{-- NOW SERVING --}}
                        <div class="bg-gradient-to-br from-blue-50/50 to-indigo-50/30 p-4 rounded-xl border border-blue-100/60 text-center flex flex-col justify-center items-center shadow-sm">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-[#1F6F8B]">Now Serving</p>
                            <p class="text-2xl font-black text-[#003366] my-1.5 tracking-tight truncate max-w-full">
                                {{ $nowServingNumber }}
                            </p>
                            <p class="text-xs font-semibold text-slate-600 truncate max-w-full">
                                <i class="fa-solid fa-user-doctor text-[#1F6F8B] text-[10px] mr-1"></i> {{ $nowServingDoctor }}
                            </p>
                        </div>

                        {{-- NEXT IN LINE --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/60 text-center flex flex-col justify-center items-center shadow-sm">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Next In Line</p>
                            <p class="text-4xl font-black text-slate-700 my-1.5 tracking-tight">
                                {{ $nextNumber }}
                            </p>
                            <p class="text-xs font-semibold text-slate-500 truncate max-w-full">
                                <i class="fa-solid fa-user-doctor text-slate-400 text-[10px] mr-1"></i> {{ $nextDoctor }}
                            </p>
                        </div>

                        {{-- QUEUE STATS / WAITING --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/60 text-center flex flex-col justify-center items-center shadow-sm">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Total Waiting</p>
                            <p class="text-4xl font-black text-[#1F6F8B] my-1.5 tracking-tight">
                                {{ $waitingCount }}
                            </p>
                            <p class="text-xs font-semibold text-slate-500">
                                <i class="fa-solid fa-users text-slate-400 text-[10px] mr-1"></i> Patients ahead
                            </p>
                        </div>

                    </div>

                    {{-- TAB 2: MY TICKET DETAILS --}}
                    <div x-show="monitorTab === 'ticket'" x-transition.opacity style="display: none;" class="bg-slate-50 p-5 rounded-xl border border-slate-200/60 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4 text-center sm:text-left">
                            <div class="w-14 h-14 rounded-xl bg-[#1F6F8B] text-white flex items-center justify-center text-2xl font-black shadow-md shrink-0">
                                <i class="fa-solid fa-ticket"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Your Assigned Queue Number</p>
                                <p class="text-3xl font-black text-[#003366] tracking-tight">{{ $userTicketNumber }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center sm:items-end gap-1.5">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Current Status</span>
                            <span class="bg-blue-100 text-[#1F6F8B] text-xs font-bold px-3.5 py-1.5 rounded-lg uppercase tracking-wider">
                                {{ str_replace('_', '-', $userQueueStatus) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex flex-row items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-rotate text-[#1F6F8B]"></i> Auto-sync active (5s)
                        </span>
                        <span class="text-xs text-[#1F6F8B] font-extrabold">
                            Clinica Bansil Queue System
                        </span>
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- ================= RIGHT UTILITY SIDEBAR ================= --}}
    <aside class="w-80 hidden lg:flex flex-col p-6 gap-6 border-l border-slate-200/50 bg-white shrink-0 h-full overflow-y-auto font-karma">
        
        @if(auth()->user()->is_locked_from_booking)
            <div class="bg-rose-50 border border-rose-100 rounded-xl p-5 text-center select-none shrink-0">
                <div class="flex items-center justify-center gap-2 text-rose-600 font-bold text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-ban"></i> Booking Suspended
                </div>
                <p class="mt-2 text-xs text-rose-700/85 leading-relaxed font-medium">
                    Your scheduling rights have been restricted. Use the appeal form on your main dashboard to request reactivation.
                </p>
            </div>
        @elseif(isset($hasActiveAppointment) && $hasActiveAppointment)
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-5 text-center select-none shrink-0">
                <div class="flex items-center justify-center gap-2 text-slate-500 font-bold text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-lock text-slate-400"></i> Booking Locked
                </div>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed font-medium">
                    Please complete your current appointment before scheduling another session.
                </p>
            </div>
        @else
            <a href="{{ route('patient.booking.step1') }}"
               class="flex items-center justify-center gap-2.5 bg-[#1F6F8B] hover:bg-[#18576d] text-white py-4 rounded-xl shadow-md font-bold text-xs uppercase tracking-wider transition duration-150 active:scale-[0.98] shrink-0">
                <i class="fa-solid fa-circle-plus text-sm"></i> 
                Book New Appointment
            </a>
        @endif

        @php
            $notifications = auth()->user()->notifications()->latest()->take(5)->get();
        @endphp

        <div class="space-y-3 overflow-y-auto flex-1 pr-1 font-karma">
            <h3 class="font-bold text-[#003366] text-xs uppercase tracking-widest flex items-center gap-2 shrink-0">
                <i class="fa-regular fa-bell text-[#1F6F8B]"></i>
                Notifications
            </h3>

            @forelse($notifications as $n)
                @php
                    $raw = $n->data;
                    if (is_string($raw)) {
                        $raw = json_decode($raw, true) ?? [];
                    }
                    $data = is_array($raw) ? $raw : [];

                    $msg = $data['message'] ?? null;
                    if (is_string($msg) && (str_starts_with($msg, '{') || str_starts_with($msg, '['))) {
                        $msg = json_decode($msg, true);
                    }

                    $displayText = null;

                    if (is_array($msg)) {
                        $status = strtolower($msg['status'] ?? $data['status'] ?? '');
                        if (in_array($status, ['no_show', 'no-show', 'noshow'])) {
                            $displayText = 'Your appointment has been marked as No-Show by the clinic.';
                        } else {
                            $displayText = 'Your appointment status was updated to: ' . ucfirst(str_replace('_', ' ', $status));
                        }
                    } elseif (is_string($msg)) {
                        $displayText = $msg;
                    }

                    if (!$displayText) {
                        $status = strtolower($data['status'] ?? '');
                        if (in_array($status, ['no_show', 'no-show', 'noshow'])) {
                            $displayText = 'Your appointment has been marked as No-Show by the clinic.';
                        } else {
                            $displayText = 'Your appointment status has been updated.';
                        }
                    }
                @endphp

                <div class="text-xs p-3 bg-white border border-slate-100 rounded-xl text-slate-600 font-medium leading-relaxed hover:bg-slate-50 transition-colors shadow-sm">
                    <p class="text-slate-700 font-semibold">
                        {{ $displayText }}
                    </p>
                    <span class="block text-[10px] text-slate-400 mt-1.5 font-semibold">
                        {{ $n->created_at ? $n->created_at->diffForHumans() : 'Just now' }}
                    </span>
                </div>
            @empty
                <div class="text-center py-6 bg-white rounded-xl border border-slate-100">
                    <i class="fa-regular fa-bell-slash text-slate-300 text-lg mb-2 block"></i>
                    <p class="text-xs text-slate-400 italic">No notifications found.</p>
                </div>
            @endforelse
        </div>
    </aside>

    {{-- ================= MOBILE BOTTOM NAVIGATION BAR ================= --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200/80 px-6 py-2.5 flex justify-around items-center z-40 shadow-lg font-karma">
        <a href="#" class="flex flex-col items-center gap-0.5 text-[#1F6F8B]">
            <i class="fa-solid fa-chart-pie text-lg"></i>
            <span class="text-[9px] font-bold uppercase tracking-wider">Home</span>
        </a>
        
        <a href="{{ route('patient.appointments') }}" class="flex flex-col items-center gap-0.5 text-slate-400 hover:text-slate-600">
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
    </nav>

</div>

{{-- ================= AUTO REFRESH QUEUE ================= --}}
<script>
function refreshQueue() {
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        let doc = new DOMParser().parseFromString(html, 'text/html');
        let newQueue = doc.querySelector("#queue-box");
        let currentQueue = document.querySelector("#queue-box");

        if (newQueue && currentQueue) {
            currentQueue.innerHTML = newQueue.innerHTML;
        }
    })
    .catch(err => console.error('Queue refresh failed:', err));
}

setInterval(refreshQueue, 5000);
</script>
</body>
</html>