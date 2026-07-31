<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctor Schedules - Clinica Bansil</title>

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

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
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

            <main class="flex-1 p-4 sm:p-6 md:p-8 overflow-y-auto">
                <div class="max-w-[1400px] mx-auto">
                    
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 sm:gap-4 mb-6 sm:mb-8 font-['Karma']" style="font-family: 'Karma', serif;">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-[#003366] tracking-tight">Available Medical Practitioners</h2>
                            <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-widest mt-0.5 sm:mt-1">Select a doctor to adjust active hours and holidays.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 font-['Karma']" style="font-family: 'Karma', serif;">
                        @foreach($doctors as $doctor)
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-6 flex flex-col justify-between hover:shadow-md transition duration-200">
                                
                                <div class="flex items-center gap-4">
                                    <img
                                        src="https://ui-avatars.com/api/?name={{ urlencode($doctor->name) }}&background=EBF4FF&color=236ff2&bold=true"
                                        class="w-14 h-14 sm:w-16 sm:h-16 rounded-full border-2 border-slate-50 shadow-sm shrink-0 object-cover"
                                        alt="{{ $doctor->name }}">

                                    <div class="min-w-0">
                                        <h3 class="font-bold text-slate-800 text-sm sm:text-base truncate">
                                            {{ $doctor->name }}
                                        </h3>
                                        <p class="text-[10px] sm:text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5 truncate">
                                            {{ $doctor->specialty ?? 'General Practitioner' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 sm:mt-6">
                                    <a href="{{ route('clerk.doctor-schedules.edit', $doctor->id) }}"
                                       class="block w-full text-center bg-[#95CCDD] hover:bg-[#3C7284] text-white py-2.5 sm:py-3 px-4 rounded-xl font-bold text-xs uppercase tracking-widest shadow-sm transition active:scale-[0.98]">
                                        <i class="fa-regular fa-calendar-check mr-1"></i> Edit Schedule
                                    </a>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
            </main>
        </div>
    </div>

</body>
</html>