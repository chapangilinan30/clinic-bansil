<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Doctor Panel') - Clinica Bansil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karma:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .brand-text-blue { color: #236ff2; }
        .brand-header-text { color: #003366; }
        .brand-subtext-gray { color: #7794a3; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f4f7fc] text-slate-700 h-screen flex flex-col overflow-hidden font-sans" x-data="{ mobileSidebarOpen: false }">

    <div class="flex-1 flex overflow-hidden relative">

        <!-- MOBILE BACKDROP -->
        <div x-cloak x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 md:hidden"></div>

        <!-- SIDEBAR -->
        <aside x-cloak :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-[#1F6F8B] text-white flex flex-col justify-between p-6 shadow-xl shrink-0 transform transition-transform duration-300 ease-in-out md:translate-x-0">
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert">
                        <h1 class="text-xl font-bold tracking-wide font-['Montserrat_Alternates']" style="font-family: 'Montserrat Alternates', sans-serif;">Clinica Bansil</h1>
                    </div>
                    <!-- Mobile close button -->
                    <button @click="mobileSidebarOpen = false" class="md:hidden text-white/80 hover:text-white">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>


                <!-- Doctor Profile Card -->
                <div class="bg-white/10 p-4 rounded-xl flex flex-col items-center text-center shadow-inner backdrop-blur-sm">
                    <div class="w-16 h-16 rounded-full bg-white border-2 border-white/40 flex items-center justify-center brand-text-blue text-2xl font-bold mb-3 shadow">
                        {{ strtoupper(substr(Auth::user()->name ?? 'D', 0, 1)) }}
                    </div>
                    <h2 class="text-sm font-semibold tracking-wide">
                        {{ Auth::user()->name ?? 'Doctor Name' }}
                    </h2>
                    <p class="text-[11px] text-blue-100 uppercase tracking-widest mt-1 opacity-90">
                        {{ Auth::user()->specialization ?? 'Specialty' }}
                    </p>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1.5 text-sm font-medium font-['Karma']" style="font-family: 'Karma', serif;">
                    <!-- DESKTOP LINKS (Original full list) -->
                    <div class="hidden md:block space-y-1.5">
                        @php
                            $desktopLinks = [
                                ['route' => 'doctor.dashboard', 'icon' => 'fa-gauge', 'label' => 'Dashboard'],
                                ['route' => 'doctor.prescriptions.create', 'icon' => 'fi fi-ts-file-medical', 'label' => 'Prescriptions', 'is_fi' => true],
                                ['route' => 'doctor.medicines.index', 'icon' => 'fa-capsules', 'label' => 'Medicines'],
                                ['route' => 'doctor.patients.index', 'icon' => 'fa-regular fa-user', 'label' => 'Patients'],
                                ['route' => 'doctor.schedules.index', 'icon' => 'fa-regular fa-calendar-plus', 'label' => 'Schedule'],
                            ];
                        @endphp
                        @foreach($desktopLinks as $link)
                            <a href="{{ route($link['route']) }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs($link['route']) ? 'bg-white brand-text-blue shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                                <i class="{{ $link['icon'] }} text-base"></i> <span>{{ $link['label'] }}</span>
                            </a>
                        @endforeach

                        @if(Auth::user()->is_admin ?? false)
                        <div class="pt-3 border-t border-white/20 mt-3">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-blue-100 hover:bg-white/10 transition-all duration-200">
                                <i class="fa-solid fa-user-shield text-base"></i>
                                <span>Admin Mode</span>
                            </a>
                        </div>
                    @endif
                    </div>

                    <!-- MOBILE LINKS (Dashboard and Schedule only, Patients removed) -->
                    <div class="md:hidden space-y-1.5">
                        @php
                            $mobileLinks = [
                                ['route' => 'doctor.dashboard', 'icon' => 'fa-gauge', 'label' => 'Dashboard'],
                                ['route' => 'doctor.schedules.index', 'icon' => 'fa-regular fa-calendar-plus', 'label' => 'Schedule'],
                            ];
                        @endphp
                        @foreach($mobileLinks as $link)
                            <a href="{{ route($link['route']) }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs($link['route']) ? 'bg-white brand-text-blue shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                                <i class="{{ $link['icon'] }} text-base"></i> <span>{{ $link['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </nav>
            </div>

            <!-- Role Switcher & Sign Out -->
            <div class="mt-auto space-y-2">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-bold tracking-wide transition w-full shadow-md">
                        <i class="fa-solid fa-gauge"></i> Admin Dashboard
                    </a>
                @endif

                @if(Auth::user()->role === 'clerk')
                    <a href="{{ route('clerk.dashboard') }}" class="flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white px-4 py-3 rounded-xl font-bold tracking-wide transition w-full shadow-md">
                        <i class="fa-solid fa-clipboard-user"></i> Clerk Dashboard
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 hover:bg-[#3C7284] text-white px-4 py-3 rounded-xl font-bold tracking-wide transition w-full shadow-md">
                        <i class="fi fi-ts-sign-out-alt"></i> Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <header class="brand-header bg-white px-4 md:px-8 py-5 flex justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm font-['Karma']" style="font-family: 'Karma', serif;">
                <div class="flex items-center gap-3">
                    <!-- Mobile Hamburger Toggle -->
                    <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="md:hidden text-slate-700 hover:text-[#1F6F8B] focus:outline-none mr-1">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-[20px] md:text-[25px] font-bold tracking-wide brand-header-text">@yield('title', 'Doctor Dashboard')</h1>
                        <p class="text-[11px] md:text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-1">Smart Healthcare System</p>
                    </div>
                </div>
                <div class="text-xs md:text-sm brand-subtext-gray brand-header-text font-bold opacity-95">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                </div>
            </header>

            <main class="flex-1 p-4 md:p-8 overflow-y-auto">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm text-sm">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
