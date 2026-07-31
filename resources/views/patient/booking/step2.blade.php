<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Step 2 - Clinica Bansil</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karma:wght@300;400;500;600;700&family=Montserrat+Alternates:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- AlpineJS -->
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
<body class="bg-[#f4f7fc] text-slate-700 h-screen flex flex-col overflow-hidden font-sans" x-data="{ sidebarOpen: false }" x-cloak>

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
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert">
                    <h1 class="text-xl font-bold tracking-wide font-montserrat">Clinica Bansil</h1>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-white/80 hover:text-white text-2xl font-bold focus:outline-none">
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
                <a href="#" class="inline-block mt-2 text-xs text-blue-200 hover:text-white font-semibold underline">
                    Edit Profile
                </a>
            </div>

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
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto pb-20 md:pb-0">

        {{-- Desktop Header Bar --}}
        <header class="bg-white px-6 md:px-8 py-5 flex justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm font-karma">
            <div>
                <h1 class="text-xl md:text-[25px] font-bold tracking-wide brand-header-text">Book an Appointment</h1>
                <p class="text-xs md:text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-0.5">
                    Smart Healthcare System
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-sm brand-subtext-gray brand-header-text font-bold opacity-90 hidden sm:block">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                </div>
                <button @click="sidebarOpen = true" class="md:hidden text-slate-600 hover:text-[#1F6F8B] text-xl p-2 focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </header>
        
        <main class="flex-1 p-4 md:p-8 space-y-6 max-w-6xl w-full mx-auto">

            {{-- Title Banner --}}
            <div class="bg-[#CBDCEB] p-6 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#003366] font-montserrat tracking-tight">Select Doctor</h1>
                    <p class="text-[#7794a3] text-xs md:text-sm mt-1 font-medium">
                        Step 2 of 5 – Doctor Selection
                    </p>
                </div>
            </div>

            <!-- PROGRESS BAR -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between text-xs sm:text-sm font-semibold text-slate-400">
                    <span class="text-[#1F6F8B]">1. Department</span>
                    <span class="text-[#1F6F8B]">2. Doctor</span>
                    <span>3. Date & Time</span>
                    <span>4. Information</span>
                    <span>5. Confirm</span>
                </div>

                <div class="mt-3 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-2 bg-[#1F6F8B] rounded-full transition-all duration-300" style="width: 40%"></div>
                </div>
            </div>

            <!-- FORM -->
            <form action="{{ route('patient.booking.step3') }}" method="GET">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- LEFT INFO CARD -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 h-fit">
                        <h2 class="text-base font-bold text-[#003366] font-montserrat mb-3">
                            Choose a Doctor
                        </h2>

                        <p class="text-xs text-slate-500 leading-relaxed">
                            Select the medical professional you would like to consult with for your appointment.
                        </p>

                        <div class="mt-6 space-y-2 text-xs text-slate-500 border-t border-slate-100 pt-4">
                            <p class="flex items-center gap-2"><i class="fa-solid fa-building-user text-[#1F6F8B]"></i> Doctors listed by chosen department</p>
                            <p class="flex items-center gap-2"><i class="fa-solid fa-user-check text-[#1F6F8B]"></i> Only one doctor can be selected</p>
                            <p class="flex items-center gap-2"><i class="fa-solid fa-calendar-day text-[#1F6F8B]"></i> Schedule depends on doctor availability</p>
                        </div>
                    </div>

                    <!-- DOCTOR SELECTION CONTAINER -->
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">

                        <h2 class="text-base font-bold text-[#003366] font-montserrat mb-6">
                            Available Doctors
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            @foreach($doctors as $doctor)
                                <label class="cursor-pointer group">
                                    <input
                                        type="radio"
                                        name="doctor_id"
                                        value="{{ $doctor->id }}"
                                        class="hidden peer"
                                        required
                                    >

                                    <div
                                        class="border border-slate-200 rounded-2xl p-5 transition-all duration-200
                                               hover:border-[#1F6F8B]
                                               peer-checked:border-[#1F6F8B]
                                               peer-checked:bg-[#CBDCEB]/20 peer-checked:shadow-sm">

                                        <div class="flex items-center gap-4">

                                            <!-- AVATAR / ICON -->
                                            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center shrink-0 border border-slate-200 group-hover:border-[#1F6F8B] transition-colors">
                                                <i class="fa-solid fa-user-doctor text-slate-400 text-lg peer-checked:text-[#1F6F8B]"></i>
                                            </div>

                                            <!-- INFO -->
                                            <div class="min-w-0 flex-1">
                                                <h3 class="font-bold text-sm text-[#003366] truncate">
                                                    {{ $doctor->name }}
                                                </h3>
                                                <p class="text-xs text-[#7794a3] font-medium mt-0.5 truncate">
                                                    {{ $doctor->specialization ?? 'General Practitioner' }}
                                                </p>
                                            </div>

                                        </div>

                                        <!-- SELECTED BADGE -->
                                        <div class="mt-3 hidden peer-checked:block">
                                            <span class="text-xs font-bold text-[#1F6F8B] flex items-center gap-1">
                                                <i class="fa-solid fa-circle-check"></i> Selected
                                            </span>
                                        </div>

                                    </div>
                                </label>
                            @endforeach

                        </div>
                    </div>
                </div>

                <!-- FOOTER BUTTONS -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-200/60">

                    <a href="{{ route('patient.booking.step1') }}"
                       class="px-6 py-3 border border-slate-300 text-slate-600 font-bold text-xs uppercase tracking-wider rounded-xl hover:bg-slate-100 transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>

                    <button
                        type="submit"
                        class="px-8 py-3 bg-[#1F6F8B] hover:bg-[#18586e] text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-md transition active:scale-[0.98] flex items-center gap-2">
                        Continue to Date & Time <i class="fa-solid fa-arrow-right"></i>
                    </button>

                </div>

            </form>

        </main>
    </div>

</div>

</body>
</html>