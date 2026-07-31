<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Clerk Panel') - Clinica Bansil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f4f7fc] text-slate-700 h-screen flex overflow-hidden font-sans">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#4988C4] text-white flex flex-col justify-between p-6 shadow-xl shrink-0">
        <div class="flex flex-col gap-8">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-2">
                <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert">
                <h1 class="text-xl font-bold tracking-wide">Clerk Panel</h1>
            </div>

            <!-- Profile -->
            <div class="bg-white/10 p-4 rounded-2xl flex flex-col items-center text-center backdrop-blur-sm">
                <div class="w-16 h-16 rounded-full bg-white text-[#4988C4] flex items-center justify-center text-2xl font-bold mb-3 shadow">
                    {{ substr(auth()->user()->name ?? 'C', 0, 1) }}
                </div>
                <h2 class="text-sm font-semibold">{{ auth()->user()->name ?? 'Clerk' }}</h2>
                <p class="text-[10px] text-blue-100 uppercase tracking-widest mt-1 opacity-80">Front Desk</p>
            </div>

            <!-- Navigation -->
            <nav class="flex flex-col gap-2">
                <a href="{{ route('clerk.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('clerk.dashboard') ? 'bg-white text-[#4988C4] font-bold shadow-md' : 'text-blue-100 hover:bg-white/10' }}">
                    <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
                </a>
                <a href="{{ route('clerk.doctor-schedules.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('clerk.doctor-schedules.*') ? 'bg-white text-[#4988C4] font-bold shadow-md' : 'text-blue-100 hover:bg-white/10' }}">
                    <i class="fa-regular fa-calendar-check"></i> <span>Schedules</span>
                </a>

                <!-- Admin Link -->
                @if(auth()->user() && auth()->user()->is_admin)
                    <div class="pt-4 mt-2 border-t border-white/20">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.*') ? 'bg-white text-[#4988C4] font-bold shadow-md' : 'text-blue-100 hover:bg-white/10' }}">
                            <i class="fa-solid fa-user-shield"></i> <span>Admin Panel</span>
                        </a>
                    </div>
                @endif
            </nav>
        </div>
        
        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-2 bg-[#d12c2c] hover:bg-red-700 text-white px-4 py-3 rounded-xl font-bold w-full transition">
                <i class="fa-solid fa-sign-out-alt"></i> Sign Out
            </button>
        </form>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white px-8 py-5 flex justify-between items-center border-b border-slate-200 shadow-sm">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain">
                <h1 class="text-xl font-bold text-[#005596]">Clinica Bansil</h1>
            </div>
            <div class="text-sm font-bold text-[#005596] bg-slate-50 px-4 py-2 rounded-lg">{{ now()->format('l, F j, Y') }}</div>
        </header>

        <main class="flex-1 p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>