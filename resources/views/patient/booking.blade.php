<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Clinica Bansil</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karma:wght@300;400;500;600;700&family=Montserrat+Alternates:wght@500;600;700&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/image-removebg-preview.png') }}" class="h-10 w-auto object-contain brightness-0 invert" alt="Clinica Bansil Logo">
                    <h1 class="text-xl font-bold tracking-wide font-montserrat">Clinica Bansil</h1>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-white/80 hover:text-white text-2xl font-bold focus:outline-none" aria-label="Close Sidebar">
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
                
            </div>

            <nav class="space-y-1.5 text-sm font-medium font-karma">
                <a href="{{ route('patient.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 text-blue-100 hover:bg-white/10">
                    <i class="fa-solid fa-chart-pie text-base"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('patient.appointments') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 bg-white brand-text-teal shadow-md font-semibold">
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

    {{-- ================= CENTER MAIN LAYOUT ================= --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        {{-- Header Bar --}}
        <header class="bg-white px-6 md:px-8 py-5 flex justify-between items-center shrink-0 border-b border-slate-200/40 shadow-sm font-karma">
            <div>
                <h1 class="text-xl md:text-[25px] font-bold tracking-wide brand-header-text">Book Appointment</h1>
                <p class="text-xs md:text-[13px] brand-subtext-gray font-semibold uppercase tracking-wider -mt-0.5">
                    Smart Healthcare System
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-sm brand-subtext-gray brand-header-text font-bold opacity-90 hidden sm:block">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d, Y') }}
                </div>
            </div>
        </header>

        {{-- Scrollable Main Content Zone --}}
        <main class="flex-1 p-4 md:p-8 overflow-y-auto pb-24 md:pb-8">
            <div class="max-w-3xl mx-auto space-y-6 font-karma">

                {{-- Success message --}}
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl text-xs font-semibold shadow-sm flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(auth()->user()->is_locked_from_booking)
                    {{-- LOCKOUT APPEAL INTERFACE AREA --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-rose-100 overflow-hidden">
                        <div class="bg-rose-50 p-6 border-b border-rose-100 flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center text-lg shrink-0 shadow-sm">
                                <i class="fa-solid fa-user-slash"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-rose-800">Booking Access Suspended</h3>
                                <p class="text-xs text-rose-700/80 font-medium mt-0.5">This account has accumulated 3 or more cancellation/no-show infractions.</p>
                            </div>
                        </div>

                        <div class="p-6">
                            {{-- Status State Check Handler --}}
                            @if(auth()->user()->reactivation_status === 'none' || !auth()->user()->reactivation_status)
                                <p class="text-slate-600 text-xs font-medium mb-6 leading-relaxed">
                                    To request the restoration of your appointment booking permissions, please provide a comprehensive explanation or official justification below regarding your missed or late-cancelled slots.
                                </p>

                                <form action="{{ route('patient.reactivate.submit') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="reactivation_reason" class="block mb-2 font-bold text-slate-700 text-xs uppercase tracking-wider">
                                            Statement of Reason / Appeal Context
                                        </label>
                                        <textarea 
                                            name="reactivation_reason" 
                                            id="reactivation_reason" 
                                            rows="5" 
                                            class="w-full text-slate-700 text-xs font-medium border border-slate-200 rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] transition shadow-sm resize-none @error('reactivation_reason') border-rose-500 @enderror" 
                                            placeholder="Explain why you missed your sessions and how you intend to fulfill future appointments..."
                                            required
                                        >{{ old('reactivation_reason') }}</textarea>
                                        
                                        @error('reactivation_reason')
                                            <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" class="flex items-center gap-2 bg-[#1F6F8B] hover:bg-[#18576d] text-white py-3 px-6 rounded-xl shadow-md font-bold text-xs uppercase tracking-wider transition active:scale-[0.98]">
                                            <i class="fa-solid fa-paper-plane"></i>
                                            Submit Reactivation Appeal
                                        </button>
                                    </div>
                                </form>

                            @elseif(auth()->user()->reactivation_status === 'pending')
                                {{-- Pending Review Frame Box Block --}}
                                <div class="bg-amber-50 border border-amber-100 rounded-xl p-5 flex items-start space-x-3 text-xs">
                                    <i class="fa-solid fa-circle-notch animate-spin text-amber-500 text-base mt-0.5 shrink-0"></i>
                                    <div>
                                        <h4 class="font-bold text-amber-900 text-sm mb-1">Appeal Under Evaluation</h4>
                                        <p class="text-amber-800 leading-relaxed font-medium">
                                            Your justification statement has been successfully logged into our server queue. A clinic receptionist or administrative clerk will inspect your file records shortly. Thank you for your patience.
                                        </p>
                                        <div class="mt-4 bg-white/80 border border-amber-200/60 p-3.5 rounded-lg text-slate-700 italic shadow-inner">
                                            <strong>Your Statement:</strong> "{{ auth()->user()->reactivation_reason }}"
                                        </div>
                                    </div>
                                </div>

                            @elseif(auth()->user()->reactivation_status === 'denied')
                                {{-- Rejected Panel Warning Area --}}
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 flex items-start space-x-3 text-xs">
                                    <i class="fa-solid fa-circle-xmark text-rose-500 text-base mt-0.5 shrink-0"></i>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm mb-1">Appeal Request Denied</h4>
                                        <p class="text-slate-600 leading-relaxed font-medium">
                                            Administrative managers have reviewed your booking history logs and formally declined this particular reactivation request. Please contact the front desk support directly or check in over the phone to clear manually outstanding discrepancies.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Booking Form --}}
                    <form method="POST" action="{{ route('patient.book-appointment') }}" class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200/80 space-y-5">
                        @csrf

                        {{-- Department --}}
                        <div>
                            <label for="department" class="block mb-2 font-bold text-slate-700 text-xs uppercase tracking-wider">Department</label>
                            <select id="department" name="department_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] transition">
                                <option value="">Select Department</option>
                                @foreach($departments as $dep)
                                    <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Doctor --}}
                        <div>
                            <label for="doctor" class="block mb-2 font-bold text-slate-700 text-xs uppercase tracking-wider">Doctor</label>
                            <select id="doctor" name="doctor_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] transition">
                                <option value="">Select Doctor</option>
                            </select>
                        </div>

                        {{-- Date --}}
                        <div>
                            <label for="appointment_date" class="block mb-2 font-bold text-slate-700 text-xs uppercase tracking-wider">Date</label>
                            <input type="date" id="appointment_date" name="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] transition" />
                        </div>

                        {{-- Time --}}
                        <div>
                            <label for="appointment_time" class="block mb-2 font-bold text-slate-700 text-xs uppercase tracking-wider">Time</label>
                            <select id="appointment_time" name="time" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#1F6F8B]/20 focus:border-[#1F6F8B] transition">
                                <option value="">Select Time</option>
                            </select>
                        </div>

                        {{-- Submit --}}
                        <div class="pt-2">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#1F6F8B] hover:bg-[#18576d] text-white py-4 rounded-xl shadow-md font-bold text-xs uppercase tracking-wider transition active:scale-[0.98]">
                                <i class="fa-solid fa-calendar-check text-sm"></i>
                                Book Appointment
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </main>
    </div>

</div>

{{-- JS for dynamic dropdowns --}}
<script>
    // Fetch doctors when department changes
    document.getElementById('department')?.addEventListener('change', function() {
        let depId = this.value;
        let docSelect = document.getElementById('doctor');
        if(!docSelect) return;
        
        docSelect.innerHTML = '<option value="">Select Doctor</option>';
        document.getElementById('appointment_date').value = '';
        document.getElementById('appointment_time').innerHTML = '<option value="">Select Time</option>';

        if (!depId) return;

        fetch(`/patient/get-doctors/${depId}`)
            .then(res => res.json())
            .then(doctors => {
                doctors.forEach(doc => {
                    let option = document.createElement('option');
                    option.value = doc.id;
                    option.text = doc.name;
                    docSelect.add(option);
                });
            });
    });

    // Fetch available dates when doctor changes
    document.getElementById('doctor')?.addEventListener('change', function() {
        let doctorId = this.value;
        let dateInput = document.getElementById('appointment_date');
        if(!dateInput) return;
        
        dateInput.value = '';
        document.getElementById('appointment_time').innerHTML = '<option value="">Select Time</option>';

        if (!doctorId) return;

        fetch(`/patient/get-dates/${doctorId}`)
            .then(res => res.json())
            .then(dates => {
                if (dates.length > 0) {
                    dateInput.setAttribute('min', dates[0]);
                    dateInput.setAttribute('max', dates[dates.length - 1]);
                }
            });
    });

    // Fetch available times when date changes
    document.getElementById('appointment_date')?.addEventListener('change', function() {
        let doctorSelect = document.getElementById('doctor');
        if(!doctorSelect) return;
        
        let doctorId = doctorSelect.value;
        let date = this.value;
        let timeSelect = document.getElementById('appointment_time');
        timeSelect.innerHTML = '<option value="">Select Time</option>';

        if (!doctorId || !date) return;

        fetch(`/patient/get-times/${doctorId}/${date}`)
            .then(res => res.json())
            .then(times => {
                if (times.length === 0) {
                    let option = document.createElement('option');
                    option.text = 'No available slots';
                    option.value = '';
                    timeSelect.add(option);
                } else {
                    times.forEach(t => {
                        let option = document.createElement('option');
                        option.value = t;
                        option.text = t;
                        timeSelect.add(option);
                    });
                }
            });
    });
</script>
</body>
</html>