<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel') - Clinica Bansil</title>
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
<body class="bg-[#f4f7fc] text-slate-700 h-screen h-[100dvh] min-h-[100svh] flex flex-col overflow-hidden font-sans" x-data="{ mobileSidebarOpen: false }">

    <div class="flex-1 min-h-0 flex overflow-hidden relative">

        <div x-cloak x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

        <!-- SIDEBAR -->
        <aside x-cloak :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-[#1F6F8B] text-white flex flex-col justify-between p-6 shadow-xl shrink-0 transform transition-transform duration-300 ease-in-out lg:translate-x-0 overflow-y-auto">
            <div class="space-y-8">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert">
                        <h1 class="text-xl font-bold tracking-wide font-['Montserrat_Alternates']">Admin Panel</h1>
                    </div>
                    <button @click="mobileSidebarOpen = false" aria-label="Close admin navigation" class="lg:hidden text-white/80 hover:text-white">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Admin Profile Card -->
                <div class="bg-white/10 p-4 rounded-xl flex flex-col items-center text-center shadow-inner backdrop-blur-sm">
                    <div class="w-16 h-16 rounded-full bg-white border-2 border-white/40 flex items-center justify-center brand-text-blue text-2xl font-bold mb-3 shadow">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <h2 class="text-sm font-semibold tracking-wide">
                        {{ Auth::user()->name ?? 'Administrator' }}
                    </h2>
                    <p class="text-[11px] text-blue-100 uppercase tracking-widest mt-1 opacity-90">
                        {{ Auth::user()->role ?? 'Admin' }}
                    </p>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-1.5 text-sm font-medium font-['Karma']" aria-label="Admin navigation" style="font-family: 'Karma', serif;">
                    @php
                        $links = [
                            ['route' => 'admin.dashboard', 'icon' => 'fa-gauge', 'label' => 'Dashboard'],
                            ['route' => 'admin.users.index', 'icon' => 'fa-user', 'label' => 'Users'],
                            ['route' => 'admin.services.index', 'icon' => 'fa-stethoscope', 'label' => 'Services'],
                            ['route' => 'admin.reports.index', 'icon' => 'fa-chart-line', 'label' => 'Reports'],
                        ];
                    @endphp
                    @foreach($links as $link)
                        <a href="{{ route($link['route']) }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs($link['route']) ? 'bg-white brand-text-blue shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
                            <i class="fa-solid {{ $link['icon'] }} text-base"></i> <span>{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Role Switcher & Sign Out -->
            <div class="mt-auto space-y-2">
                @if(Auth::user()->role === 'doctor')
                    <a href="{{ route('doctor.dashboard') }}" class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-3 rounded-xl font-bold tracking-wide transition w-full shadow-md">
                        <i class="fa-solid fa-stethoscope"></i> Doctor Dashboard
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
                        <i class="fa-solid fa-sign-out-alt"></i> Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 min-w-0 flex flex-col overflow-hidden">
            <header class="brand-header bg-white px-4 sm:px-8 py-4 sm:py-5 flex justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm font-['Karma']" style="font-family: 'Karma', serif;">
                <div class="flex items-center gap-3">
                    <button @click="mobileSidebarOpen = true" aria-label="Open admin navigation" :aria-expanded="mobileSidebarOpen.toString()" class="lg:hidden shrink-0 text-slate-700 hover:text-[#1F6F8B] focus:outline-none focus:ring-2 focus:ring-[#1F6F8B] rounded">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-[25px] font-bold tracking-wide leading-tight brand-header-text">@yield('title', 'Admin Panel')</h1>
                        <p class="hidden sm:block text-[11px] sm:text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-1">Smart Healthcare System</p>
                    </div>
                </div>
                <div class="text-xs sm:text-sm brand-subtext-gray brand-header-text font-bold opacity-90 hidden sm:block">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                </div>
            </header>

            <main class="flex-1 min-h-0 min-w-0 p-4 sm:p-6 md:p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>

    </div>
    @stack('scripts')
</body>
</html>