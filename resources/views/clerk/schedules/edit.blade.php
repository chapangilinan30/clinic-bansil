<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Availability Settings - Clinica Bansil</title>

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

<body x-data="{ mobileMenuOpen: false }" class="bg-[#f4f7fc] text-slate-700 h-screen flex flex-col overflow-hidden font-sans">

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

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
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

                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Notifications Dropdown Component -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-slate-600 hover:text-brand-text-blue transition focus:outline-none">
                            <i class="fa-regular fa-bell text-lg sm:text-xl"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                            @endif
                        </button>

                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-3 z-50 flex flex-col max-h-96"
                             style="display: none;">
                            
                            <div class="px-4 pb-2 border-b border-slate-100 flex justify-between items-center">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Notifications</h3>
                                <span class="text-[10px] bg-blue-50 text-blue-600 font-semibold px-2 py-0.5 rounded-full">
                                    {{ auth()->user()->notifications()->count() }} Total
                                </span>
                            </div>

                            <div class="space-y-2 overflow-y-auto flex-1 px-3 py-2">
                                @php
                                    $notifications = auth()->user()->notifications()->latest()->take(5)->get();
                                @endphp

                                @forelse($notifications as $n)
                                    @php
                                        $data = is_string($n->data) ? json_decode($n->data, true) : $n->data;
                                        $msg = is_array($data) 
                                            ? ($data['message'] ?? $data['body'] ?? $data['title'] ?? 'New appointment update.') 
                                            : $n->data;
                                    @endphp
                                    <div class="text-xs p-3 bg-slate-50 border border-slate-100 rounded-lg text-slate-600 font-medium leading-relaxed hover:bg-slate-100/60 transition-colors">
                                        <p class="text-slate-700">
                                            {{ is_string($msg) ? $msg : 'You have a new appointment update.' }}
                                        </p>
                                        <span class="block text-[10px] text-slate-400 mt-1 font-semibold">{{ $n->created_at ? $n->created_at->diffForHumans() : 'Just now' }}</span>
                                    </div>
                                @empty
                                    <div class="py-6 text-center">
                                        <i class="fa-regular fa-bell-slash text-slate-300 text-lg mb-2 block"></i>
                                        <p class="text-xs text-slate-400 italic">No notifications found.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Date Display -->
                    <div class="text-xs sm:text-sm brand-subtext-gray brand-header-text font-bold opacity-90 border-l border-slate-200 pl-2 sm:pl-4">
                        {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 md:p-8 overflow-y-auto font-['Karma']" style="font-family: 'Karma', serif;">
                
                <div class="max-w-[1400px] mx-auto">

                    <!-- Title & Back Row -->
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 sm:gap-0 mb-6">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#003366] tracking-tight">
                                {{ $doctor->name ?? 'Doctor' }}'s Schedule Configurations
                            </h2>
                            <p class="text-[10px] sm:text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                Set up recurring weekly blocks or flag specific dates off
                            </p>
                        </div>

                        <a href="{{ route('clerk.doctor-schedules.index') }}" 
                           class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm border border-slate-200/60 active:scale-95 w-fit">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </a>
                    </div>

                    @if($errors->has('time_error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                            <span>{{ $errors->first('time_error') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        <!-- Weekly Availability Section -->
                        <div class="lg:col-span-2">
                            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-100">
                                <div class="flex justify-between items-center mb-6">
                                    <div>
                                        <h3 class="text-base sm:text-lg font-bold text-[#003366]">Weekly Availability</h3>
                                        <p class="text-[11px] sm:text-xs text-slate-400 font-medium">Create time blocks and apply them to multiple days of the week.</p>
                                    </div>
                                    <button type="button" onclick="addTimeBlock()" class="bg-blue-50 border border-blue-200 text-[#1F6F8B] px-3.5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-blue-100 transition-colors flex items-center gap-1.5 shrink-0">
                                        <i class="fa-solid fa-plus"></i> Add Block
                                    </button>
                                </div>

                                <form id="scheduleForm" method="POST" action="{{ route('clerk.doctor-schedules.store') }}">
                                    @csrf

                                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                    <input type="hidden" name="type" value="weekly">

                                    <div id="timeBlocksContainer" class="space-y-4">
                                        <div class="time-block-card bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200 relative group transition-all">
                                            <button type="button" onclick="removeTimeBlock(this)" class="absolute top-4 right-4 text-slate-400 hover:text-red-500 transition-colors text-xs font-bold uppercase tracking-wider flex items-center gap-1 hidden group-hover:flex">
                                                <i class="fa-solid fa-trash-can"></i> Remove
                                            </button>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">From</label>
                                                    <input type="time" class="start-time-input w-full border border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] bg-white text-sm font-semibold text-slate-700" required>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">To</label>
                                                    <input type="time" class="end-time-input w-full border border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] bg-white text-sm font-semibold text-slate-700" required>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Applies to</label>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach(['Monday' => 'M', 'Tuesday' => 'T', 'Wednesday' => 'W', 'Thursday' => 'Th', 'Friday' => 'F', 'Saturday' => 'Sa', 'Sunday' => 'Su'] as $fullName => $shortName)
                                                        <button type="button" 
                                                                onclick="toggleDayBadge(this, '{{ $fullName }}')" 
                                                                class="day-badge w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 font-bold text-xs flex items-center justify-center hover:border-[#1F6F8B] hover:text-[#1F6F8B] transition-all select-none shadow-xs">
                                                            {{ $shortName }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="hiddenSubmissionsContainer"></div>

                                    <button type="submit" class="mt-6 w-full bg-[#1F6F8B] hover:bg-[#1A5B72] text-white font-bold text-xs uppercase tracking-widest px-4 py-3.5 rounded-xl shadow-md transition-colors active:scale-98">
                                        Save Weekly Schedule
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Leave & Holidays Sidebar Card -->
                        <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-100 h-fit">
                            <h3 class="text-base sm:text-lg font-bold text-[#003366] mb-0.5">Leave & Holidays</h3>
                            <p class="text-xs text-slate-400 font-medium mb-4">Mark specific dates you are entirely unavailable.</p>

                            <form method="POST" action="{{ route('clerk.doctor-schedules.unavailability.store') }}" class="flex gap-2 mb-4">
                                @csrf
                                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                
                                <input type="date" name="date" class="border border-slate-200 rounded-xl px-3 py-2 w-full text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] bg-slate-50/50" required>
                                
                                <button type="submit" class="bg-[#1F6F8B] hover:bg-[#1A5B72] text-white px-4 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors shadow-xs">
                                    Add
                                </button>
                            </form>

                            <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                                @forelse($unavailabilities ?? [] as $item)
                                    <div class="flex justify-between items-center bg-slate-50 border border-slate-100 px-3.5 py-2.5 rounded-xl">
                                        <span class="text-xs text-slate-700 font-bold">{{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</span>
                                        <form method="POST" action="{{ route('clerk.doctor-schedules.unavailability.destroy', $item->id) }}">
                                            @csrf
                                            
                                        </form>
                                    </div>
                                @empty
                                    <div class="text-center py-6 border border-dashed border-slate-200 rounded-xl">
                                        <p class="text-xs text-slate-400 font-semibold italic">No unavailable dates set.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>

                </div>
            </main>
        </div>
    </div>

    <script>
    // Toggle day badge state values
    function toggleDayBadge(button, dayName) {
        if (button.dataset.selected === "true") {
            button.dataset.selected = "false";
            button.classList.remove('bg-[#1F6F8B]', 'text-white', 'border-[#1F6F8B]', 'shadow-sm');
            button.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
        } else {
            button.dataset.selected = "true";
            button.dataset.day = dayName;
            button.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
            button.classList.add('bg-[#1F6F8B]', 'text-white', 'border-[#1F6F8B]', 'shadow-sm');
        }
    }

    // Add another Time Card Block
    function addTimeBlock() {
        const container = document.getElementById('timeBlocksContainer');
        const blueprint = `
            <div class="time-block-card bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-200 relative group transition-all animate-fadeIn">
                <button type="button" onclick="removeTimeBlock(this)" class="absolute top-4 right-4 text-slate-400 hover:text-red-500 transition-colors text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-trash-can"></i> Remove
                </button>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">From</label>
                        <input type="time" class="start-time-input w-full border border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] bg-white text-sm font-semibold text-slate-700" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">To</label>
                        <input type="time" class="end-time-input w-full border border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] bg-white text-sm font-semibold text-slate-700" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Applies to</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="toggleDayBadge(this, 'Monday')" class="day-badge w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 font-bold text-xs flex items-center justify-center hover:border-[#1F6F8B] hover:text-[#1F6F8B] transition-all select-none shadow-xs">M</button>
                        <button type="button" onclick="toggleDayBadge(this, 'Tuesday')" class="day-badge w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 font-bold text-xs flex items-center justify-center hover:border-[#1F6F8B] hover:text-[#1F6F8B] transition-all select-none shadow-xs">T</button>
                        <button type="button" onclick="toggleDayBadge(this, 'Wednesday')" class="day-badge w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 font-bold text-xs flex items-center justify-center hover:border-[#1F6F8B] hover:text-[#1F6F8B] transition-all select-none shadow-xs">W</button>
                        <button type="button" onclick="toggleDayBadge(this, 'Thursday')" class="day-badge w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 font-bold text-xs flex items-center justify-center hover:border-[#1F6F8B] hover:text-[#1F6F8B] transition-all select-none shadow-xs">Th</button>
                        <button type="button" onclick="toggleDayBadge(this, 'Friday')" class="day-badge w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 font-bold text-xs flex items-center justify-center hover:border-[#1F6F8B] hover:text-[#1F6F8B] transition-all select-none shadow-xs">F</button>
                        <button type="button" onclick="toggleDayBadge(this, 'Saturday')" class="day-badge w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 font-bold text-xs flex items-center justify-center hover:border-[#1F6F8B] hover:text-[#1F6F8B] transition-all select-none shadow-xs">Sa</button>
                        <button type="button" onclick="toggleDayBadge(this, 'Sunday')" class="day-badge w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 font-bold text-xs flex items-center justify-center hover:border-[#1F6F8B] hover:text-[#1F6F8B] transition-all select-none shadow-xs">Su</button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', blueprint);
    }

    // Remove an added Card Block
    function removeTimeBlock(button) {
        const cards = document.querySelectorAll('.time-block-card');
        if (cards.length > 1) {
            button.closest('.time-block-card').remove();
        } else {
            alert("You must keep at least one time block.");
        }
    }

    // Map frontend data to back-end compatible array schema before submit
    document.getElementById('scheduleForm').addEventListener('submit', function(e) {
        const hiddenContainer = document.getElementById('hiddenSubmissionsContainer');
        hiddenContainer.innerHTML = '';

        const cards = document.querySelectorAll('.time-block-card');
        let dynamicInputHTML = '';
        let selectedAnyDay = false;

        cards.forEach(card => {
            const startTime = card.querySelector('.start-time-input').value;
            const endTime = card.querySelector('.end-time-input').value;
            const badges = card.querySelectorAll('.day-badge[data-selected="true"]');

            badges.forEach(badge => {
                const dayName = badge.dataset.day;
                selectedAnyDay = true;

                dynamicInputHTML += `
                    <input type="hidden" name="days[${dayName}][active]" value="1">
                    <input type="hidden" name="days[${dayName}][start_time]" value="${startTime}">
                    <input type="hidden" name="days[${dayName}][end_time]" value="${endTime}">
                    <input type="hidden" name="days[${dayName}][slot_duration]" value="20">
                `;
            });
        });

        if (!selectedAnyDay) {
            e.preventDefault();
            alert('Please select at least one day for your time configurations.');
            return;
        }

        hiddenContainer.innerHTML = dynamicInputHTML;
    });
    </script>
</body>
</html>