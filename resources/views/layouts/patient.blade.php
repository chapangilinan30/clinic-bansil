<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Clinica Bansil') }} - Patient Portal</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Karma:wght@300;400;500;600;700&family=Montserrat+Alternates:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

    {{-- ================= SIDEBAR ================= --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
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
                <a href="#" class="inline-block mt-2 text-xs text-blue-200 hover:text-white font-semibold underline">
                    Edit Profile
                </a>
            </div>

            {{-- Navigation Links --}}
            <nav class="space-y-1.5 text-sm font-medium font-karma">
                <a href="{{ route('patient.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('patient.dashboard') ? 'bg-white brand-text-teal shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                    <i class="fa-solid fa-chart-pie text-base"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('patient.appointments') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('patient.appointments*') ? 'bg-white brand-text-teal shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                    <i class="fa-regular fa-calendar-check text-base"></i>
                    <span>Appointments</span>
                </a>

                <a href="{{ route('patient.history') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('patient.history*') ? 'bg-white brand-text-teal shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
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
            <div>
                <h1 class="text-xl md:text-[25px] font-bold tracking-wide brand-header-text">
                    @yield('header-title', 'Patient Dashboard')
                </h1>
                <p class="text-xs md:text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-0.5">
                    Smart Healthcare System
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="md:hidden text-[#1F6F8B] text-2xl p-1 focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="text-sm brand-subtext-gray brand-header-text font-bold opacity-90 hidden sm:block">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                </div>
            </div>
        </header>

        {{-- Scrollable Main Content Zone --}}
        <main class="flex-1 p-4 md:p-8 overflow-y-auto pb-24 md:pb-8">
            <div class="max-w-[1400px] mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

</div>

{{-- ================= MOBILE BOTTOM NAVIGATION BAR ================= --}}
<nav class="md:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200/80 z-30 flex justify-around items-center h-16 px-2 font-karma shadow-lg">
    <a href="{{ route('patient.dashboard') }}" 
       class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('patient.dashboard') ? 'brand-text-teal font-bold border-t-2 border-[#1F6F8B]' : 'text-slate-400 hover:text-[#1F6F8B] transition-colors' }}">
        <i class="fa-solid fa-chart-pie text-lg"></i>
        <span class="text-[10px] {{ request()->routeIs('patient.dashboard') ? '' : 'font-semibold' }} mt-1">Dashboard</span>
    </a>

    <a href="{{ route('patient.appointments') }}" 
       class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('patient.appointments*') ? 'brand-text-teal font-bold border-t-2 border-[#1F6F8B]' : 'text-slate-400 hover:text-[#1F6F8B] transition-colors' }}">
        <i class="fa-regular fa-calendar-check text-lg"></i>
        <span class="text-[10px] {{ request()->routeIs('patient.appointments*') ? '' : 'font-semibold' }} mt-1">Appointments</span>
    </a>

    <a href="{{ route('patient.history') }}" 
       class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('patient.history*') ? 'brand-text-teal font-bold border-t-2 border-[#1F6F8B]' : 'text-slate-400 hover:text-[#1F6F8B] transition-colors' }}">
        <i class="fa-solid fa-clock-rotate-left text-lg"></i>
        <span class="text-[10px] {{ request()->routeIs('patient.history*') ? '' : 'font-semibold' }} mt-1">History</span>
    </a>
</nav>

</body>
</html>