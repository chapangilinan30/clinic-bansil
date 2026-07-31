@extends('layouts.doctor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 font-['Karma']" style="font-family: 'Karma', serif;">

    <!-- Top Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
        <div>
            <h2 class="text-2xl font-bold text-[#003366] tracking-tight">
                Create Prescription
            </h2>
            <p class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider mt-1.5">
                Select a patient from the directory to start writing a prescription.
            </p>
        </div>
        
        <!-- Progress Badge -->
        <div class="flex items-center gap-2.5 bg-[#f4f7fc] border border-[#CBDCEB]/60 px-4 py-2 rounded-xl self-start sm:self-center shadow-xs">
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#003366] text-white text-[10px] font-bold shadow-xs">1</span>
            <span class="text-xs font-bold text-[#003366] uppercase tracking-wider">Patient Select</span>
            <span class="text-[#7794a3] text-xs font-bold px-1">&rarr;</span>
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#CBDCEB] text-[#003366] text-[10px] font-bold">2</span>
            <span class="text-xs font-bold text-[#7794a3] uppercase tracking-wider">Medications</span>
        </div>
    </div>

    <!-- Main Search and Directory Container -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Search Header Bar -->
        <div class="p-6 md:p-8 border-b border-slate-100 bg-[#f4f7fc]">
            <form method="GET" action="{{ route('doctor.prescriptions.create') }}" class="space-y-3">
                <label for="search" class="block text-xs font-bold text-[#7794a3] uppercase tracking-wider">
                    Search Patient Directory
                </label>
                
                <div class="flex flex-col sm:flex-row gap-3 font-sans">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#7794a3]">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </span>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Enter name, patient ID, or keyword..."
                               class="w-full bg-white border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-sm text-[#003366] placeholder-slate-400 focus:border-[#0992C2] focus:ring-4 focus:ring-[#0992C2]/15 focus:outline-none transition-all shadow-xs">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full sm:w-auto font-['Karma']">
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 text-xs font-bold text-white uppercase tracking-wider bg-[#003366] hover:bg-[#0B2D72] active:scale-[0.98] rounded-xl shadow-xs focus:outline-none focus:ring-4 focus:ring-[#003366]/20 transition-all duration-200 cursor-pointer">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Search</span>
                        </button>

                        @if(request('search'))
                            <a href="{{ route('doctor.prescriptions.create') }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 text-xs font-bold text-[#003366] uppercase tracking-wider bg-[#CBDCEB]/40 hover:bg-[#CBDCEB]/80 active:scale-[0.98] rounded-xl border border-[#003366]/10 transition-all duration-200 cursor-pointer">
                                <i class="fa-solid fa-xmark text-sm"></i>
                                <span>Clear</span>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Patient Listing Section -->
        <div class="p-6 md:p-8 space-y-6">
            
            <div class="flex justify-between items-center">
                <h3 class="text-xs font-bold text-[#0B2D72] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-address-book text-[#7794a3] text-sm"></i>
                    <span>Patient Records Directory</span>
                </h3>
                <span class="bg-[#CBDCEB]/50 text-[#003366] text-[10px] font-bold px-3 py-1 rounded-lg tracking-wider uppercase border border-[#003366]/10">
                    Matches: {{ method_exists($patients, 'total') ? $patients->total() : $patients->count() }}
                </span>
            </div>

            <div class="space-y-3">
                @forelse($patients as $patient)
                    <div class="group p-5 bg-slate-50/30 border border-slate-100 hover:border-[#0992C2]/40 hover:bg-white hover:shadow-md rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-all duration-200">
                        
                        <!-- Avatar & Details -->
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 shrink-0 rounded-xl bg-[#CBDCEB]/40 border border-[#003366]/10 group-hover:bg-[#003366] flex items-center justify-center text-[#003366] group-hover:text-white font-bold text-sm tracking-wider transition-all duration-200 shadow-xs">
                                {{ strtoupper(substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1)) }}
                            </div>
                            
                            <div class="min-w-0">
                                <p class="font-bold text-[#003366] text-base group-hover:text-[#0992C2] transition-colors truncate">
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                </p>
                                <p class="text-xs text-[#7794a3] font-semibold mt-0.5 uppercase tracking-wider flex items-center gap-1.5">
                                    <span>Patient ID:</span> 
                                    <span class="text-[#003366] font-mono font-bold">#{{ $patient->id }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('doctor.prescriptions.create.patient', $patient->id) }}"
                           class="w-full sm:w-auto shrink-0 text-center bg-white group-hover:bg-[#003366] text-[#003366] group-hover:text-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl border border-slate-200 group-hover:border-[#003366] transition-all duration-200 flex items-center justify-center gap-2 shadow-xs active:scale-95">
                            <span>Select Patient</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="py-16 px-6 rounded-2xl text-center border-2 border-dashed border-slate-200 bg-slate-50/30 space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-[#CBDCEB]/40 border border-[#003366]/10 flex items-center justify-center mx-auto text-[#7794a3] shadow-xs">
                            <i class="fa-solid fa-user-slash text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#003366]">
                                No Patient Records Found
                            </h4>
                            <p class="text-xs text-[#7794a3] max-w-sm mx-auto leading-relaxed mt-1">
                                @if(request('search'))
                                    We couldn't find any matches for "<span class="text-[#003366] font-bold">{{ request('search') }}</span>". Try double-checking spelling or patient ID structure.
                                @else
                                    There are currently no patient records available in the master database directory.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            @if(method_exists($patients, 'links'))
                <div class="mt-8 border-t border-slate-100 pt-6">
                    {{ $patients->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection