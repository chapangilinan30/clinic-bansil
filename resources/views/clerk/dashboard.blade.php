<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clerk Dashboard - Clinica Bansil</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karma:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .brand-sidebar-blue { background-color: #236ff2; }
        .brand-text-blue { color: #236ff2; }
        .brand-header-bg { background-color: #CBDCEB; }
        .brand-header-text { color: #003366; }
        .brand-subtext-gray { color: #7794a3; }
    </style>
</head>

<body x-data="{ mobileMenuOpen: false, activeTab: 'monitor-totals' }" class="bg-[#f4f7fc] text-slate-700 h-screen flex flex-col overflow-hidden font-sans">

    <!-- Mobile Drawer Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false" 
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 md:hidden" 
         style="display: none;"></div>

    <!-- Mobile Slide-out Sidebar -->
    <aside x-show="mobileMenuOpen"
           x-transition:enter="transition ease-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 w-64 bg-[#1F6F8B] text-white flex flex-col justify-between p-6 z-50 md:hidden shadow-2xl"
           style="display: none;">
        <div class="space-y-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert">
                    <h1 class="text-xl font-bold tracking-wide font-['Montserrat_Alternates']" style="font-family: 'Montserrat Alternates', sans-serif;">Clinica Bansil</h1>
                </div>
                <button @click="mobileMenuOpen = false" class="text-white/80 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <div class="bg-white/10 p-4 rounded-xl flex flex-col items-center text-center shadow-inner backdrop-blur-sm">
                <div class="w-16 h-16 rounded-full bg-white border-2 border-white/40 flex items-center justify-center brand-text-blue text-2xl font-bold mb-3 shadow">
                    {{ substr(Auth::user()->name ?? 'C', 0, 1) }}
                </div>
                <h2 class="text-sm font-semibold tracking-wide">
                    {{ Auth::user()->name }}
                </h2>
                <p class="text-[11px] text-blue-100 uppercase tracking-widest mt-1 opacity-90">
                    {{ Auth::user()->role }}
                </p>
            </div>

            <nav class="space-y-1.5 text-sm font-medium font-['Karma']" style="font-family: 'Karma', serif;">
                <a href="{{ route('clerk.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('clerk.dashboard') ? 'bg-white brand-text-blue shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                    <i class="fa-solid fa-gauge text-base"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('clerk.doctor-schedules.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('clerk.doctor-schedules.*') ? 'bg-white brand-text-blue shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                    <i class="fa-regular fa-calendar-plus text-base"></i>
                    <span>Schedules</span>
                </a>

                @if(Auth::user()->is_admin ?? false)
                <div class="pt-4 border-t border-white/20 mt-4">
                    <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white hover:bg-white/20 transition-all font-semibold border border-white/20 shadow-inner">
                        <i class="fa-solid fa-user-shield text-base"></i> <span>Admin Mode</span>
                    </a>
                </div>
                @endif
            </nav>
        </div>

        <div class="mt-auto space-y-2 pt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center justify-center gap-2 hover:bg-[#3C7284] text-white px-4 py-3 rounded-xl font-bold tracking-wide transition w-full shadow-md">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex overflow-hidden">

        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex w-64 bg-[#1F6F8B] text-white flex-col justify-between p-6 shadow-xl shrink-0">
            <div class="space-y-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert">
                    <h1 class="text-xl font-bold tracking-wide font-['Montserrat_Alternates']" style="font-family: 'Montserrat Alternates', sans-serif;">Clinica Bansil</h1>
                </div>

                <div class="bg-white/10 p-4 rounded-xl flex flex-col items-center text-center shadow-inner backdrop-blur-sm">
                    <div class="w-16 h-16 rounded-full bg-white border-2 border-white/40 flex items-center justify-center brand-text-blue text-2xl font-bold mb-3 shadow">
                        {{ substr(Auth::user()->name ?? 'C', 0, 1) }}
                    </div>
                    <h2 class="text-sm font-semibold tracking-wide">
                        {{ Auth::user()->name }}
                    </h2>
                    <p class="text-[11px] text-blue-100 uppercase tracking-widest mt-1 opacity-90">
                        {{ Auth::user()->role }}
                    </p>
                </div>

                <nav class="space-y-1.5 text-sm font-medium font-['Karma']" style="font-family: 'Karma', serif;">
                    <a href="{{ route('clerk.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('clerk.dashboard') ? 'bg-white brand-text-blue shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fa-solid fa-gauge text-base"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('clerk.doctor-schedules.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('clerk.doctor-schedules.*') ? 'bg-white brand-text-blue shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fa-regular fa-calendar-plus text-base"></i>
                        <span>Schedules</span>
                    </a>

                    @if(Auth::user()->is_admin ?? false)
                    <div class="pt-4 border-t border-white/20 mt-4">
                        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/10 text-white hover:bg-white/20 transition-all font-semibold border border-white/20 shadow-inner">
                            <i class="fa-solid fa-user-shield text-base"></i> <span>Admin Mode</span>
                        </a>
                    </div>
                    @endif
                </nav>
            </div>

            <div class="mt-auto space-y-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center justify-center gap-2 hover:bg-[#3C7284] text-white px-4 py-3 rounded-xl font-bold tracking-wide transition w-full shadow-md">
                        <i class="fa-solid fa-sign-out-alt"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden pb-[65px] md:pb-0">
            
            <!-- Header -->
            <header class="brand-header bg-white px-4 sm:px-8 py-3 sm:py-5 flex justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm font-['Karma']" style="font-family: 'Karma', serif;">
                <div class="flex items-center gap-3">
                    <button @click="mobileMenuOpen = true" class="md:hidden text-[#003366] p-1.5 focus:outline-none hover:bg-slate-100 rounded-lg">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-[25px] font-bold tracking-wide brand-header-text leading-tight">Clerk's Dashboard</h1>
                        <p class="text-[10px] sm:text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-0.5">Smart Healthcare System</p>
                    </div>
                </div>
                <div class="text-xs sm:text-sm brand-subtext-gray brand-header-text font-bold opacity-90 text-right">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                </div>
            </header>

            <main class="flex-1 p-3 sm:p-6 md:p-8 overflow-y-auto">
                
                <div class="max-w-[1400px] mx-auto">
                    <div class="grid grid-cols-12 gap-4 sm:gap-6">

                        {{-- LEFT COLUMN --}}
                        <div class="col-span-12 lg:col-span-3 space-y-4 font-['Karma']" style="font-family: 'Karma', serif;">
                            
                            {{-- Assignment Date (Always Visible) --}}
                            <div class="bg-white p-3.5 sm:p-5 rounded-xl border border-slate-100 shadow-sm">
                                <h3 class="text-[13px] sm:text-[15px] font-black text-[#003366] mb-2 sm:mb-3 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-days text-sm"></i> Assignment Date
                                </h3>
                                <form action="{{ route('clerk.dashboard') }}" method="GET">
                                    <input type="date" name="schedule_date" 
                                    value="{{ $selectedDate }}" 
                                    onchange="this.form.submit()"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs sm:text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#0992C2]/20 outline-none transition font-['Karma']">
                                </form>
                            </div>

                            {{-- DOCTORS DIRECTORY --}}
                            <div :class="activeTab === 'directory-queue' ? 'block' : 'hidden md:block'"
                                 x-data="{ selectedDoctor: 'all' }" 
                                 class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                                <div class="w-full flex justify-between items-center px-5 py-4 font-bold text-[15px] text-[#003366] bg-white border-b border-slate-100 font-['Karma']" style="font-family: 'Karma', serif;">
                                    <span class="flex items-center gap-2">
                                        <i class="fa-solid fa-user-doctor text-[#003366]"></i> Doctors Directory
                                    </span>
                                </div>
                                <div class="bg-white divide-y divide-slate-50 max-h-[260px] md:max-h-none overflow-y-auto">
                                    <button @click="selectedDoctor = 'all'; showAllQueue()" class="w-full text-left px-5 py-3 font-bold text-xs uppercase tracking-wider transition-all duration-150 pl-4 outline-none">
                                        Show All Availability
                                    </button>
                                    @foreach($doctors_data as $doc)
                                    @php $isUnavailable = $doc['status'] === 'Unavailable'; @endphp
                                    <div @click="selectedDoctor = '{{ $doc['name'] }}'; filterQueueByDoctor('{{ $doc['name'] }}')"
                                         :class="selectedDoctor === '{{ $doc['name'] }}' ? 'bg-blue-50 border-l-4 border-[#0992C2]' : 'hover:bg-slate-100 border-l-4 border-transparent hover:border-slate-300'"
                                         class="flex justify-between items-center pr-5 py-3 cursor-pointer transition-all duration-150 pl-4">
                                        <div>
                                            <p class="font-semibold text-sm text-slate-700">{{ $doc['name'] }}</p>
                                            <p class="text-xs flex items-center gap-1.5 mt-0.5 {{ $isUnavailable ? 'text-red-500' : 'text-emerald-500' }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $isUnavailable ? 'bg-red-500' : 'bg-emerald-500' }}"></span>
                                                {{ $isUnavailable ? 'Unavailable' : 'Available' }}
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN --}}
                        <div class="col-span-12 lg:col-span-9 space-y-4">
                            
                            {{-- TOTAL TODAY CARDS --}}
                            @php $noShows = $queue->where('status', 'no-show')->count(); @endphp
                            <div :class="activeTab === 'monitor-totals' ? 'grid' : 'hidden md:grid'" 
                                 class="grid-cols-2 md:grid-cols-4 gap-3">
                                <div class="bg-[#BFDDF0] py-4 px-3 rounded-xl border border-slate-100 shadow-sm text-center">
                                    <p class="text-[10px] font-bold text-[#0B2D72] uppercase tracking-wider mb-1">Total Today</p>
                                    <p class="text-2xl font-black text-[#003366]">{{ $totalToday }}</p>
                                </div>
                                <div class="bg-[#BFDDF0] py-4 px-3 rounded-xl border border-slate-100 shadow-sm text-center">
                                    <p class="text-[10px] font-bold text-[#57595B] uppercase tracking-wider mb-1">Pending</p>
                                    <p class="text-2xl font-black text-[#003366]">{{ $pending }}</p>
                                </div>
                                <div class="bg-[#BFDDF0] py-4 px-3 rounded-xl border border-slate-100 shadow-sm text-center">
                                    <p class="text-[10px] font-bold text-[#266210] uppercase tracking-wider mb-1">Checked In</p>
                                    <p class="text-2xl font-black text-[#003366]">{{ $checkedIn }}</p>
                                </div>
                                <div class="bg-[#FFB2B2] py-4 px-3 rounded-xl border border-slate-100 shadow-sm text-center">
                                    <p class="text-[10px] font-bold text-[#7B2525] uppercase tracking-wider mb-1">No-Show</p>
                                    <p class="text-2xl font-black text-[#7B2525]">{{ $noShows }}</p>
                                </div>
                            </div>

                            {{-- LIVE QUEUE MONITOR --}}
                            @php
                                $nowServingItem = $queue->first(fn($i) => in_array(strtolower($i->status), ['in-progress', 'called']));
                                $nowServingNumber = $nowServingItem ? $nowServingItem->queue_number : '--';
                                $nowServingDoctor = $nowServingItem ? ($nowServingItem->doctor->name ?? $nowServingItem->doctor_name ?? 'N/A') : 'None';

                                $nextItem = $queue->first(fn($i) => in_array(strtolower($i->status), ['checked-in', 'pending', 'booked']));
                                $nextNumber = $nextItem ? $nextItem->queue_number : '--';
                                $nextDoctor = $nextItem ? ($nextItem->doctor->name ?? $nextItem->doctor_name ?? 'N/A') : 'None';

                                $waitingCount = $queue->filter(fn($i) => in_array(strtolower($i->status), ['checked-in', 'pending', 'booked']))->count();
                            @endphp

                            <div :class="activeTab === 'monitor-totals' ? 'block' : 'hidden md:block'" 
                                 id="queue-box" class="bg-[#BFDDF0] text-[#003366] rounded-2xl p-4 sm:p-6 shadow-md relative overflow-hidden font-['Karma'] mb-4 md:mb-6" style="font-family: 'Karma', serif;">

                                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#003366]/5 rounded-full blur-xl pointer-events-none"></div>

                                <div class="flex justify-between items-center mb-2 border-b border-[#003366]/20 pb-3">
                                    <h2 class="text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                        Live Queue Monitor
                                    </h2>
                                    <span class="text-[10px] bg-[#003366]/10 px-2.5 py-0.5 rounded-full uppercase font-bold tracking-wider opacity-90">Real-Time</span>
                                </div>

                                {{-- Main Display: Now Serving vs Next Up --}}
                                <div class="grid grid-cols-2 gap-3 sm:gap-4 my-2">
                                    
                                    {{-- NOW SERVING --}}
                                    <div class="bg-white/60 p-3 sm:p-4 rounded-xl border border-[#003366]/10 text-center flex flex-col justify-center items-center shadow-sm">
                                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-[#003366]/70">NOW SERVING</p>
                                        <p class="text-4xl sm:text-5xl md:text-6xl font-black text-[#003366] my-1 tracking-tight">
                                            {{ $nowServingNumber }}
                                        </p>
                                        <p class="text-[10px] sm:text-xs font-semibold text-[#0992C2] truncate max-w-full">
                                            <i class="fa-solid fa-user-doctor text-[9px] mr-1"></i> {{ $nowServingDoctor }}
                                        </p>
                                    </div>

                                    {{-- NEXT IN LINE --}}
                                    <div class="bg-white/40 p-3 sm:p-4 rounded-xl border border-[#003366]/10 text-center flex flex-col justify-center items-center shadow-sm">
                                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-[#003366]/70">NEXT IN LINE</p>
                                        <p class="text-4xl sm:text-5xl md:text-6xl font-black text-[#003366]/80 my-1 tracking-tight">
                                            {{ $nextNumber }}
                                        </p>
                                        <p class="text-[10px] sm:text-xs font-semibold text-slate-500 truncate max-w-full">
                                            <i class="fa-solid fa-user-doctor text-[9px] mr-1"></i> {{ $nextDoctor }}
                                        </p>
                                    </div>

                                </div>

                                {{-- Bottom Operational Bar --}}
                                <div class="mt-3 sm:mt-4 pt-2.5 sm:pt-3 border-t border-[#003366]/20 flex flex-row items-center justify-between text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-[#003366]">
                                    <span class="flex items-center gap-1">
                                        <i class="fa-solid fa-users text-[#003366]"></i> Waiting: <strong class="text-sm sm:text-base font-black ml-0.5">{{ $waitingCount }}</strong>
                                    </span>
                                    <span class="flex items-center gap-1 text-[9px] sm:text-xs bg-[#003366]/10 px-2 py-0.5 sm:px-3 sm:py-1 rounded-lg">
                                        <i class="fa-solid fa-arrow-right-long text-[#003366]"></i> Advance queue below
                                    </span>
                                </div>
                            </div>

                            {{-- LIVE PATIENT QUEUE --}}
                            <div :class="activeTab === 'directory-queue' ? 'block' : 'hidden md:block'" 
                                 class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0 mb-4 pb-3 sm:pb-4 border-b border-slate-100">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                            <i class="fa-solid fa-list-ol"></i> Live Patient Queue
                                        </span>
                                        <a href="{{ route('clerk.appointments.cancel.view',['date'=>$selectedDate]) }}"
                                           class="text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg transition self-start sm:self-auto">
                                            <i class="fa-solid fa-calendar-xmark mr-1"></i> Cancel Appointment
                                        </a>
                                    </div>

                                    <div id="queue-list" class="space-y-3 overflow-y-auto max-h-[300px] sm:max-h-[320px] pr-1">
                                        @forelse($queue as $item)
                                            @php
                                                $status = strtolower($item->status ?: 'pending');
                                                $config = match($status) {
                                                    'pending', 'booked' => ['border'=>'border-slate-100 bg-white', 'num'=>'bg-[#CBDCEB]/50 text-[#005596]', 'next_status'=>'checked-in', 'btn_text'=>'Check In', 'btn_color'=>'bg-emerald-500 hover:bg-emerald-600', 'icon'=>'fa-check'],
                                                    'checked-in' => ['border'=>'border-orange-200 bg-orange-50/5', 'num'=>'bg-orange-400 text-white', 'next_status'=>'called', 'btn_text'=>'Start', 'btn_color'=>'bg-orange-400 hover:bg-orange-500', 'icon'=>'fa-bullhorn'],
                                                    'called' => ['border'=>'border-blue-200 bg-blue-50/5', 'num'=>'bg-[#0992C2]/50 text-[#005596]', 'next_status'=>'in-progress', 'btn_text'=>'In Progress', 'btn_color'=>'bg-[#0992C2] hover:bg-[#077ca5]', 'icon'=>'fa-spinner fa-spin'],
                                                    'in-progress' => ['border'=>'border-blue-300/30 bg-blue-50/5', 'num'=>'bg-[#005596] text-white', 'next_status'=>'completed', 'btn_text'=>'Complete', 'btn_color'=>'bg-[#005596] hover:bg-[#004173]', 'icon'=>'fa-flag-checkered'],
                                                    'no-show' => ['border'=>'border-red-200 bg-red-50/10', 'num'=>'bg-red-300 text-white', 'next_status'=>null, 'btn_text'=>'No-Show', 'btn_color'=>'bg-red-300 pointer-events-none', 'icon'=>'fa-user-slash'],
                                                    'cancelled' => ['border'=>'border-red-200 bg-red-50/30', 'num'=>'bg-red-400 text-white', 'next_status'=>null, 'btn_text'=>'Cancelled', 'btn_color'=>'bg-red-400', 'icon'=>'fa-ban'],
                                                    'completed' => ['border'=>'border-slate-100 opacity-50 bg-slate-50/50', 'num'=>'bg-slate-100 text-slate-400', 'next_status'=>null, 'btn_text'=>'Done', 'btn_color'=>'bg-slate-100 text-slate-400 pointer-events-none', 'icon'=>'fa-check-double'],
                                                    default => ['border'=>'border-slate-100', 'num'=>'bg-[#CBDCEB]/50', 'next_status'=>'checked-in', 'btn_text'=>'Check In', 'btn_color'=>'bg-emerald-500', 'icon'=>'fa-check'],
                                                };
                                            @endphp
                                            <div class="queue-item flex items-center justify-between p-3 rounded-lg border-2 {{ $config['border'] }} shadow-sm transition-all duration-200" data-doctor-name="{{ $item->doctor->name ?? $item->doctor_name }}">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 {{ $config['num'] }} rounded-md flex shrink-0 items-center justify-center font-black text-lg shadow-inner border border-black/5">
                                                        {{ $item->queue_number }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <span class="text-xs sm:text-sm font-bold text-slate-700 capitalize truncate block">{{ $item->patient_name ?? 'Guest Patient' }}</span>
                                                        <p class="text-[10px] sm:text-[11px] text-slate-400 mt-0.5 font-bold uppercase tracking-tight truncate">
                                                            {{ $item->appointment_time }} <span class="mx-0.5 text-slate-200">•</span> <span class="text-[#0992C2]">{{ $item->doctor->name ?? $item->doctor_name }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="shrink-0 pl-1">
                                                    @if($config['next_status'])
                                                        <form action="{{ route('clerk.appointments.update-status', $item->id) }}" method="POST">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="status" value="{{ $config['next_status'] }}">
                                                            <button type="submit" class="flex items-center gap-1 px-3 py-1.5 {{ $config['btn_color'] }} text-white rounded-md text-[9px] sm:text-[10px] font-black uppercase tracking-wider shadow-sm transition-all active:scale-95">
                                                                <i class="fa-solid {{ $config['icon'] }}"></i> {{ $config['btn_text'] }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-400 rounded-md text-[9px] sm:text-[10px] font-black uppercase tracking-wider block">{{ $config['btn_text'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-12 border border-dashed border-slate-200 rounded-lg text-slate-400 text-sm italic">No patients in queue.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </main>

            <!-- Bottom Mobile Navigation Bar (Only visible on screens smaller than md) -->
            <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-lg z-30 flex justify-around items-center h-[65px] font-['Karma']">
                
                <!-- Tab 1: Live Monitor & Totals -->
                <button @click="activeTab = 'monitor-totals'" 
                        :class="activeTab === 'monitor-totals' ? 'text-[#1F6F8B] border-t-2 border-[#1F6F8B] font-bold bg-blue-50/40' : 'text-slate-400 hover:text-slate-600'" 
                        class="flex flex-col items-center justify-center w-1/2 h-full transition">
                    <div class="flex items-center gap-1.5 mb-1">
                        <i class="fa-solid fa-tv text-base"></i>
                        <i class="fa-solid fa-chart-pie text-xs opacity-80"></i>
                    </div>
                    <span class="text-[10px] uppercase tracking-wider font-semibold">Monitor & Totals</span>
                </button>

                <!-- Tab 2: Directory & Patient Queue -->
                <button @click="activeTab = 'directory-queue'" 
                        :class="activeTab === 'directory-queue' ? 'text-[#1F6F8B] border-t-2 border-[#1F6F8B] font-bold bg-blue-50/40' : 'text-slate-400 hover:text-slate-600'" 
                        class="flex flex-col items-center justify-center w-1/2 h-full transition">
                    <div class="flex items-center gap-1.5 mb-1">
                        <i class="fa-solid fa-user-doctor text-base"></i>
                        <i class="fa-solid fa-list-ol text-xs opacity-80"></i>
                    </div>
                    <span class="text-[10px] uppercase tracking-wider font-semibold">Directory & Queue</span>
                </button>

            </nav>

        </div>
    </div>

<script>
function filterQueueByDoctor(doctorName) {
    const queueItems = document.querySelectorAll('#queue-list .queue-item');
    queueItems.forEach(item => {
        item.style.display = (item.dataset.doctorName === doctorName) ? 'flex' : 'none';
    });
}
function showAllQueue() {
    const queueItems = document.querySelectorAll('#queue-list .queue-item');
    queueItems.forEach(item => item.style.display = 'flex');
}
</script>
</body>
</html>