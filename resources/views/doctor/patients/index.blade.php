@extends('layouts.doctor')

@section('content')

<div class="max-w-5xl mx-auto space-y-6 font-['Karma']" style="font-family: 'Karma', serif;">

    {{-- Top Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-[#003366] tracking-tight">
                Patient Records
            </h2>
            <p class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider mt-1.5">
                View, search, and manage clinical patient databases.
            </p>
        </div>

        {{-- Updated Primary "Add Patient" Button --}}
        <a href="{{ route('doctor.patients.create') }}"
           class="inline-flex items-center gap-2 bg-[#1F6F8B] hover:bg-[#165267] active:bg-[#113f50] text-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 shadow-xs self-start sm:self-auto font-['Karma']">
            <i class="fa-solid fa-user-plus text-xs"></i>
            Add Patient
        </a>
    </div>

    {{-- Main Container Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">

        {{-- Search Header Bar --}}
        <div class="p-6 md:p-8 border-b border-slate-100 bg-[#f4f7fc]">
            <form method="GET" action="{{ route('doctor.patients.index') }}" class="space-y-3">
                <label class="block text-xs font-bold text-[#7794a3] uppercase tracking-wider">
                    Search Directory
                </label>
                
                <div class="flex flex-col sm:flex-row gap-3 font-sans">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#7794a3]">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </span>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by patient name..."
                               class="w-full bg-white border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-sm text-[#003366] placeholder-slate-400 focus:border-[#1F6F8B] focus:ring-4 focus:ring-[#1F6F8B]/15 focus:outline-none transition-all shadow-xs">
                    </div>

                    <div class="flex gap-2 font-['Karma']">
                        {{-- Updated Search Button --}}
                        <button type="submit"
                                class="bg-[#1F6F8B] hover:bg-[#165267] active:bg-[#113f50] text-white px-6 py-3 text-xs font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 shadow-xs flex items-center gap-2 justify-center w-full sm:w-auto">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Search
                        </button>

                        @if(request('search'))
                            <a href="{{ route('doctor.patients.index') }}"
                               class="bg-[#CBDCEB]/40 hover:bg-[#1F6F8B] text-[#003366] hover:text-white px-5 py-3 text-xs font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 flex items-center gap-2 justify-center border border-[#003366]/10 hover:border-transparent">
                                <i class="fa-solid fa-xmark text-sm"></i>
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Container --}}
        <div class="p-6 md:p-8 space-y-6">
            
            <div class="flex justify-between items-center">
                <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-users text-[#1F6F8B] text-sm"></i>
                    Active Patient Records
                </h3>
                <span class="bg-[#1F6F8B]/10 text-[#1F6F8B] text-[10px] font-bold px-3 py-1 rounded-lg tracking-wider uppercase border border-[#1F6F8B]/20">
                    Total Results: {{ $patients->count() }}
                </span>
            </div>

            @if($patients->count())
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 text-[#7794a3] text-[11px] font-bold uppercase tracking-wider bg-[#f4f7fc]">
                                    <th class="py-4 px-6">Name</th>
                                    <th class="py-4 px-6">Birth Date</th>
                                    <th class="py-4 px-6">Gender</th>
                                    <th class="py-4 px-6">Contact</th>
                                    <th class="py-4 px-6 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($patients as $patient)
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        
                                        {{-- Patient Avatar & Name --}}
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3.5">
                                                <div class="w-10 h-10 rounded-xl bg-[#1F6F8B]/10 border border-[#1F6F8B]/20 group-hover:bg-[#1F6F8B] flex items-center justify-center text-[#1F6F8B] group-hover:text-white font-bold text-xs tracking-wider transition-all duration-200 shrink-0">
                                                    {{ strtoupper(substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-[#003366] text-sm group-hover:text-[#1F6F8B] transition-colors">
                                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                                    </p>
                                                    <p class="text-[10px] text-[#7794a3] font-mono font-semibold mt-0.5">
                                                        ID: #{{ $patient->id }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Birth Date --}}
                                        <td class="py-4 px-6 text-xs text-[#003366] font-semibold">
                                            {{ \Carbon\Carbon::parse($patient->birth_date)->format('M d, Y') }}
                                        </td>

                                        {{-- Gender Badge --}}
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider border {{ strtolower($patient->gender) === 'male' ? 'bg-[#1F6F8B]/10 text-[#1F6F8B] border-[#1F6F8B]/20' : 'bg-pink-50 text-pink-700 border-pink-200/60' }}">
                                                {{ ucfirst($patient->gender) }}
                                            </span>
                                        </td>

                                        {{-- Contact Number --}}
                                        <td class="py-4 px-6 text-xs text-[#7794a3] font-mono font-semibold">
                                            {{ $patient->contact_number ?? 'N/A' }}
                                        </td>

                                        {{-- Action Button --}}
                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('doctor.patients.show', $patient) }}"
                                               class="inline-flex items-center gap-1.5 bg-white group-hover:bg-[#1F6F8B] text-[#003366] group-hover:text-white px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl border border-slate-200 group-hover:border-[#1F6F8B] transition-all duration-200 shadow-xs">
                                                View Record
                                                <i class="fa-solid fa-chevron-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="py-16 px-6 rounded-xl text-center border-2 border-dashed border-slate-200 bg-slate-50/30">
                    <div class="w-14 h-14 rounded-2xl bg-[#1F6F8B]/10 border border-[#1F6F8B]/20 flex items-center justify-center mx-auto mb-4 text-[#1F6F8B]">
                        <i class="fa-solid fa-user-slash text-xl"></i>
                    </div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#003366] mb-1.5">
                        No Patients Found
                    </h4>
                    <p class="text-xs text-[#7794a3] max-w-sm mx-auto leading-relaxed">
                        @if(request('search'))
                            We couldn't find matches for "<span class="text-[#003366] font-bold">{{ request('search') }}</span>". Try checking the spelling or query parameter.
                        @else
                            There are currently no patients registered in the clinic system database.
                        @endif
                    </p>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection