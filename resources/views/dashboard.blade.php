<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .brand-sidebar-blue { background-color: #236ff2; }
        .brand-text-blue { color: #236ff2; }
        .brand-header-bg { background-color: #CBDCEB; }
        .brand-header-text { color: #005596; }
        .brand-subtext-gray { color: #7794a3; }
    </style>
</head>

<body class="bg-[#f4f7fc] text-slate-700 h-screen flex flex-col overflow-hidden font-sans">

    <nav x-data="{ open: false }" class="bg-[rgba(13,152,186,0.2)] border-b border-[#cceaf3] shrink-0">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-center gap-3">
                    <img src="{{ asset('Image/image-removebg-preview.png') }}" class="h-8 w-8 object-contain">
                    <div class="flex flex-col justify-center leading-tight">
                        <h1 class="text-lg font-bold text-[#003366] whitespace-nowrap">Clinica Bansil</h1>
                        <p class="text-xs text-gray-500 whitespace-nowrap">Smart Healthcare System</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-100 transition shadow-sm">
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="ml-2 h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 0 010-1.414z"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Logout
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="sm:hidden flex items-center ml-2">
                    <button @click="open = ! open" class="p-2 rounded-md text-gray-400 hover:bg-gray-100 transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6L6 6z" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </nav>
    <div class="flex-1 flex overflow-hidden">

        <aside class="hidden md:flex w-64 bg-[#0992C2] text-white flex-col justify-between p-6 shadow-xl shrink-0">
            <div class="space-y-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert">
                    <h1 class="text-xl font-bold tracking-wide">Clinica Bansil</h1>
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

                <nav class="space-y-1.5 text-sm font-medium">
                    <a href="{{ route('clerk.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 bg-white brand-text-blue shadow-md font-semibold">
                        <i class="fa-solid fa-gauge text-base"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('clerk.doctor-schedules.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-white/10">
                        <i class="fa-regular fa-calendar-plus text-base"></i>
                        <span>Schedules</span>
                    </a>
                </nav>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <button type="submit"
                        class="flex items-center justify-center gap-2 bg-[#d12c2c] hover:bg-red-700 text-white px-4 py-3 rounded-xl font-bold tracking-wide transition w-full shadow-md">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    Sign Out
                </button>
            </form>
        </aside>
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="brand-header-bg px-8 py-5 flex justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain">
                    <div>
                        <h1 class="text-xl font-bold tracking-wide brand-header-text">My Appointments</h1>
                        <p class="text-[10px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-1">Smart Healthcare System</p>
                    </div>
                </div>
                <div class="text-sm brand-header-text font-bold opacity-90">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                </div>
            </header>

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                
                <div class="max-w-[1400px] mx-auto">
                    <div class="grid grid-cols-12 gap-6">

                        {{-- LEFT COLUMN: Calendar & Doctors --}}
                        <div class="col-span-12 lg:col-span-3 space-y-4">
                            {{-- Calendar --}}
                            <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm">
                                <h3 class="text-[10px] font-black brand-text-blue mb-3 uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-days text-sm"></i> Assignment Date
                                </h3>
                                <form action="{{ route('clerk.dashboard') }}" method="GET">
                                    <input type="date" name="schedule_date" 
                                           value="{{ $selectedDate }}" 
                                           onchange="this.form.submit()"
                                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#0992C2]/20 outline-none transition">
                                </form>
                            </div>

                            {{-- Doctors List --}}
                            <div x-data="{ menu: 'none' }"
                                 class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

                                {{-- Doctors Toggle Button with Contextual Color --}}
                                <button
                                 @click="menu = menu === 'doctors' ? 'none' : 'doctors'"
                                 :class="menu === 'doctors' ? 'bg-slate-50' : 'bg-white hover:bg-slate-50'"
                                 class="w-full flex justify-between items-center px-5 py-4 font-bold text-sm text-slate-700 transition duration-200">
                                    <span class="flex items-center gap-2">
                                         <i class="fa-solid fa-user-doctor brand-text-blue"></i> Doctors Directory
                                    </span>
                                    <i class="fa-solid text-slate-400 text-xs transition-transform duration-200"
                                       :class="menu == 'doctors' ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                </button>

                                {{-- Doctor Availability --}}
                                <div x-show="menu=='doctors'" x-transition class="bg-white border-t border-slate-100 divide-y divide-slate-50">

                                    <button
                                    onclick="showAllQueue()"
                                    class="w-full text-left px-5 py-2.5 hover:bg-slate-50 font-bold text-xs text-slate-500 uppercase tracking-wider">
                                         Show All Availability
                                    </button>

                                    @foreach($doctors_data as $doc)
                                        @php
                                        $isUnavailable = $doc['status'] === 'Unavailable';
                                        @endphp

                                        <div class="flex justify-between items-center px-5 py-3 cursor-pointer hover:bg-slate-50/80 transition"
                                             onclick="filterQueueByDoctor('{{ $doc['name'] }}')">

                                            <div>
                                                <p class="font-semibold text-sm text-slate-700">{{ $doc['name'] }}</p>
                                                <p class="text-xs flex items-center gap-1.5 mt-0.5 {{ $isUnavailable ? 'text-red-500' : 'text-emerald-500' }}">
                                                    <span class="h-1.5 w-1.5 rounded-full {{ $isUnavailable ? 'bg-red-500' : 'bg-emerald-500' }}"></span>
                                                    {{ $isUnavailable ? 'Unavailable' : 'Available' }}
                                                </p>
                                            </div>

                                            <form
                                                action="{{ route('clerk.doctors.toggle-availability',$doc['id']) }}"
                                                method="POST"
                                                onclick="event.stopPropagation()">
                                                @csrf
                                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                                <button class="focus:outline-none transition active:scale-95">
                                                    <i class="fa-solid {{ $isUnavailable ? 'fa-toggle-off text-slate-300' : 'fa-toggle-on text-[#0992C2]' }} text-xl"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Manage Schedules --}}
                                <a href="{{ route('clerk.doctor-schedules.index') }}"
                                   class="block border-t border-slate-100 px-5 py-3.5 font-bold text-xs text-slate-700 bg-slate-50 hover:bg-slate-100/70 text-center transition">
                                    <i class="fa-solid fa-calendar-days mr-1.5 brand-text-blue"></i> Manage Schedules
                                </a>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN (Expanded): Queue Stats and List --}}
                        <div class="col-span-12 lg:col-span-9 space-y-4">
                            {{-- Stats --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach(['Total Today'=>$totalToday, 'Pending'=>$pending, 'Checked In'=>$checkedIn, 'Walk-ins'=>$walkIns] as $label=>$count)
                                    <div class="bg-white py-4 px-3 rounded-xl border border-slate-100 shadow-sm text-center">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $label }}</p>
                                        <p class="text-2xl font-black brand-text-blue">{{ $count }}</p>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Queue List --}}
                            <div class="bg-white p-6 rounded-xl border border-slate-100 min-h-[615px] shadow-sm">
                                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                        <i class="fa-solid fa-list-ol"></i> Live Patient Queue
                                    </span>
                                    <a href="{{ route('clerk.appointments.cancel.view',['date'=>$selectedDate]) }}"
                                       class="text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 hover:bg-red-100 px-3 py-2 rounded-lg transition">
                                        <i class="fa-solid fa-calendar-xmark mr-1"></i> Cancel Appointment
                                    </a>
                                </div>

                                <div id="queue-list" class="space-y-3">
                                    @forelse($queue as $item)
                                        @php
                                            $status = strtolower($item->status ?: 'pending');
                                            $config = match($status) {
                                                'pending', 'booked' => ['border'=>'border-slate-100 bg-white', 'num'=>'bg-[#CBDCEB]/50 text-[#005596]', 'next_status'=>'checked-in', 'btn_text'=>'Check In', 'btn_color'=>'bg-emerald-500 hover:bg-emerald-600', 'icon'=>'fa-check'],
                                                'checked-in' => ['border'=>'border-orange-200 bg-orange-50/5', 'num'=>'bg-orange-400 text-white', 'next_status'=>'called', 'btn_text'=>'Start', 'btn_color'=>'bg-orange-400 hover:bg-orange-500', 'icon'=>'fa-bullhorn'],
                                                'called' => ['border'=>'border-blue-200 bg-blue-50/5', 'num'=>'bg-[#0992C2]/50 text-[#005596]', 'next_status'=>'in-progress', 'btn_text'=>'In Progress', 'btn_color'=>'bg-[#0992C2] hover:bg-[#077ca5]', 'icon'=>'fa-spinner fa-spin'],
                                                'in-progress' => ['border'=>'border-blue-300/30 bg-blue-50/5', 'num'=>'bg-[#005596] text-white', 'next_status'=>'completed', 'btn_text'=>'Complete', 'btn_color'=>'bg-[#005596] hover:bg-[#004173]', 'icon'=>'fa-flag-checkered'],
                                                'cancelled' => ['border'=>'border-red-200 bg-red-50/30', 'num'=>'bg-red-400 text-white', 'next_status'=>null, 'btn_text'=>'Cancelled', 'btn_color'=>'bg-red-400', 'icon'=>'fa-ban'],
                                                'completed' => ['border'=>'border-slate-100 opacity-50 bg-slate-50/50', 'num'=>'bg-slate-100 text-slate-400', 'next_status'=>null, 'btn_text'=>'Done', 'btn_color'=>'bg-slate-100 text-slate-400 pointer-events-none', 'icon'=>'fa-check-double'],
                                                default => ['border'=>'border-slate-100', 'num'=>'bg-[#CBDCEB]/50', 'next_status'=>'checked-in', 'btn_text'=>'Check In', 'btn_color'=>'bg-emerald-500', 'icon'=>'fa-check'],
                                            };
                                        @endphp

                                        <div class="queue-item flex items-center justify-between p-3 rounded-lg border-2 {{ $config['border'] }} shadow-sm transition-all duration-200"
                                             data-doctor-name="{{ $item->doctor->name ?? $item->doctor_name }}">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 {{ $config['num'] }} rounded-md flex items-center justify-center font-black text-xl shadow-inner border border-black/5">
                                                    {{ $item->queue_number }}
                                                </div>
                                                <div>
                                                    @if($item->user_id)
                                                        <a href="{{ route('doctor.prescriptions.create.patient', $item->user_id) }}?appointment={{ $item->id }}" 
                                                           class="text-sm font-bold text-slate-700 capitalize hover:underline transition">
                                                           {{ $item->patient_name ?? $item->user->name ?? 'Guest Patient' }}
                                                        </a>
                                                    @else
                                                        <span class="text-sm font-bold text-slate-700 capitalize">
                                                           {{ $item->patient_name ?: 'Guest Patient' }}
                                                        </span>
                                                    @endif
                                                    <p class="text-[11px] text-slate-400 mt-0.5 font-bold uppercase tracking-tight">
                                                        {{ $item->appointment_time }} <span class="mx-1 text-slate-200">•</span> <span class="text-[#0992C2]">{{ $item->doctor->name ?? $item->doctor_name }}</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="pr-2">
                                                @if($config['next_status'])
                                                    <form action="{{ route('clerk.appointments.update-status', $item->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $config['next_status'] }}">
                                                        <button type="submit" class="flex items-center gap-1.5 px-4 py-1.5 {{ $config['btn_color'] }} text-white rounded-md text-[10px] font-black uppercase tracking-wider shadow-sm transition-all active:scale-95">
                                                            <i class="fa-solid {{ $config['icon'] }}"></i> {{ $config['btn_text'] }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="px-4 py-1.5 bg-slate-50 border border-slate-200 text-slate-400 rounded-md text-[10px] font-black uppercase tracking-wider">Finished</span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-16 border border-dashed border-slate-200 rounded-lg text-slate-400 text-sm italic">No patients in queue.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
            </main>
        </div>
        </div>
    {{-- FILTER SCRIPT --}}
<script>
function filterQueueByDoctor(doctorName) {
    const queueItems = document.querySelectorAll('#queue-list .queue-item');
    const filterIndicator = document.getElementById('filter-indicator');
    queueItems.forEach(item => {
        item.style.display = (item.dataset.doctorName === doctorName) ? 'flex' : 'none';
    });
    if(filterIndicator) {
        filterIndicator.classList.remove('hidden');
    }
}
function showAllQueue() {
    const queueItems = document.querySelectorAll('#queue-list .queue-item');
    const filterIndicator = document.getElementById('filter-indicator');
    queueItems.forEach(item => item.style.display = 'flex');
    if(filterIndicator) {
        filterIndicator.classList.add('hidden');
    }
}
</script>
</body>
</html>