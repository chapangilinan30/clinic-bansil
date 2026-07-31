<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Appointments - Clinica Bansil</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700&display=swap" rel="stylesheet">
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

<body class="bg-[#f4f7fc] text-slate-700 h-screen flex flex-col overflow-hidden font-sans" 
      x-data="{ 
          openModal: false, 
          selectedAppts: [],
          toggleSelectAll(event) {
              if (event.target.checked) {
                  this.selectedAppts = [
                      @foreach($appointments as $appointment)
                      {
                          id: '{{ $appointment->id }}',
                          name: '{{ $appointment->patient_name }}',
                          time: '{{ $appointment->appointment_time }}',
                          doctor: '{{ $appointment->doctor_name }}'
                      },
                      @endforeach
                  ];
              } else {
                  this.selectedAppts = [];
              }
          }
      }">

    <div class="flex-1 flex overflow-hidden">

        <!-- Sidebar -->
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
    {{-- Dashboard Link --}}
    <a href="{{ route('clerk.dashboard') }}"
       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('clerk.dashboard') ? 'bg-white brand-text-blue shadow-md font-semibold' : 'text-blue-100 hover:bg-white/10' }}">
        <i class="fa-solid fa-gauge text-base"></i>
        <span>Dashboard</span>
    </a>

    {{-- Schedules Link --}}
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
                            class="flex items-center justify-center gap-2  hover:bg-[#3C7284] text-white px-4 py-3 rounded-xl font-bold tracking-wide transition w-full shadow-md">
                        <i class="fa-solid fa-sign-out-alt"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Header -->
            <header class="brand-header bg-white px-8 py-5 flex justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm font-['Karma']" style="font-family: 'Karma', serif;">
                <div class="flex items-center gap-3">
                    <div>
                        <h1 class="text-[25px] font-bold tracking-wide brand-header-text">Clerk's Dashboard</h1>
                        <p class="text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-1">Smart Healthcare System</p>
                    </div>
                </div>
                <div class="text-sm brand-subtext-gray brand-header-text font-bold opacity-90">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                </div>
            </header>

            <main class="flex-1 p-6 md:p-8 overflow-y-auto font-['Karma']" style="font-family: 'Karma', serif;">
                
                <div class="max-w-[1400px] mx-auto">
                    
                    <!-- Title Row with back button -->
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-[#003366] tracking-tight">
                                Cancel Doctor Appointments
                            </h2>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">
                                Date: {{ $date ?? $selectedDate }}
                            </p>
                        </div>

                        <a href="{{ route('clerk.dashboard') }}" 
                           class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition shadow-sm border border-slate-200/60 self-start sm:self-auto active:scale-95">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <!-- Appointments Selector Table Card -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        
                        <!-- Card Header Actions -->
                        <div class="bg-slate-50/75 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <input 
                                    type="checkbox" 
                                    @change="toggleSelectAll($event)"
                                    :checked="selectedAppts.length === {{ $appointments->count() }} && {{ $appointments->count() }} > 0"
                                    class="w-4.5 h-4.5 rounded text-[#1F6F8B] focus:ring-[#1F6F8B]/20 border-slate-300 cursor-pointer">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Select All Page Items</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400" x-text="selectedAppts.length + ' selected'"></span>
                        </div>

                        <div class="p-6 space-y-3">
                            @forelse($appointments as $appointment)
                                <div class="flex items-center justify-between p-3.5 rounded-xl border-2 transition-all duration-150 shadow-sm cursor-pointer"
                                     :class="selectedAppts.some(a => a.id == '{{ $appointment->id }}') ? 'border-[#95CCDD] bg-blue-50/10' : 'border-slate-100 bg-white hover:border-slate-200'">
                                    
                                    <div class="flex items-center gap-4">
                                        <!-- Selection Checkbox -->
                                        <input 
                                            type="checkbox"
                                            :checked="selectedAppts.some(a => a.id == '{{ $appointment->id }}')"
                                            @change="
                                                if ($el.checked) {
                                                    selectedAppts.push({
                                                        id: '{{ $appointment->id }}',
                                                        name: '{{ $appointment->patient_name }}',
                                                        time: '{{ $appointment->appointment_time }}',
                                                        doctor: '{{ $appointment->doctor_name }}'
                                                    });
                                                } else {
                                                    selectedAppts = selectedAppts.filter(a => a.id != '{{ $appointment->id }}');
                                                }
                                            "
                                            class="w-4.5 h-4.5 rounded text-[#1F6F8B] focus:ring-[#1F6F8B]/20 border-slate-300 cursor-pointer">

                                        <div>
                                            <h3 class="font-bold text-slate-800 capitalize text-base">
                                                {{ $appointment->patient_name }}
                                            </h3>
                                            <p class="text-xs text-slate-400 mt-0.5 font-bold uppercase tracking-tight">
                                                Time: {{ $appointment->appointment_time }} 
                                                <span class="mx-1 text-slate-300">•</span> 
                                                Doctor: <span class="text-[#003366] font-bold">{{ $appointment->doctor_name }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <span class="text-[10px] font-black uppercase tracking-wider bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-md">
                                        {{ $appointment->status }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-16 border border-dashed border-slate-200 rounded-xl text-slate-400 text-sm italic">
                                    No appointments found for this date.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Sticky/Float Action Footer -->
                    <div x-show="selectedAppts.length > 0" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="mt-6 flex justify-start">
                        <button
                            type="button"
                            @click="openModal = true"
                            class="bg-red-600 hover:bg-red-700 text-white px-7 py-3.5 rounded-xl font-bold tracking-wider transition shadow-lg active:scale-95 flex items-center gap-2 text-xs uppercase">
                            <i class="fa-solid fa-trash-can"></i>
                            Cancel Selected (<span x-text="selectedAppts.length"></span>)
                        </button>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Bulk Cancellation Popup Modal -->
    <div 
        x-show="openModal" 
        class="fixed inset-0 z-50 overflow-y-auto font-['Karma']" 
        style="display: none; font-family: 'Karma', serif;">
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <!-- Backdrop -->
            <div 
                x-show="openModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="openModal = false"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Box -->
            <div 
                x-show="openModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                
                <form action="{{ route('clerk.appointments.cancel') }}" method="POST" class="flex flex-col">
                    @csrf
                    
                    <!-- Dynamic Hidden inputs for selection delivery to Laravel -->
                    <template x-for="appt in selectedAppts" :key="appt.id">
                        <input type="hidden" name="appointment_ids[]" :value="appt.id">
                    </template>

                    <!-- Modal Header -->
                    <div class="bg-[#1F6F8B] px-6 py-5 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-xl text-orange-300"></i>
                            <h2 class="text-lg font-bold">Confirm Bulk Cancellation</h2>
                        </div>
                        <button type="button" @click="openModal = false" class="text-white hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-4">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            You are about to cancel the following (<span x-text="selectedAppts.length"></span>) appointments:
                        </p>

                        <!-- Visual list of target appointments inside modal -->
                        <div class="max-h-40 overflow-y-auto border border-slate-100 bg-slate-50/50 rounded-xl p-3 divide-y divide-slate-100">
                            <template x-for="appt in selectedAppts" :key="appt.id">
                                <div class="py-2 flex items-center justify-between text-xs font-semibold">
                                    <span class="text-slate-700 capitalize" x-text="appt.name"></span>
                                    <span class="text-slate-400 font-normal">
                                        <span x-text="appt.time"></span> · <span class="text-[#003366] font-bold" x-text="appt.doctor"></span>
                                    </span>
                                </div>
                            </template>
                        </div>

                        <!-- Single Shared Cancellation Reason -->
                        <div class="space-y-2">
                            <label for="cancellation_reason" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">
                                Reason for Cancellation <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                required
                                id="cancellation_reason" 
                                name="reason" 
                                rows="3" 
                                placeholder="Please provide a valid reason (e.g., Doctor not available, clinic maintenance...)"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:ring-2 focus:ring-[#95CCDD]/40 focus:border-[#1F6F8B] outline-none transition resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-slate-50 px-6 py-4 flex gap-3 justify-end border-t border-slate-100">
                        <button 
                            type="button" 
                            @click="openModal = false"
                            class="bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 font-bold text-xs uppercase tracking-widest px-5 py-3 rounded-xl transition duration-150">
                            Keep Appointments
                        </button>
                        <button 
                            type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase tracking-widest px-5 py-3 rounded-xl transition duration-150 shadow-md active:scale-95">
                            Cancel Selected Appointments
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>