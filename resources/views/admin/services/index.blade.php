@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div x-data="{
    openAddService: false,
    openEditService: null,
    openViewDoctors: null,
    doctors: @js($doctors),
    openView(name) { this.openViewDoctors = name; },
    closeView() { this.openViewDoctors = null; },
    getDoctors() {
        if (!this.openViewDoctors) return [];
        return this.doctors.filter(d =>
            d.specialization &&
            d.specialization.toLowerCase().trim() === this.openViewDoctors.toLowerCase().trim()
        );
    }
}">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#003366]">Services Management</h1>
            <p class="text-slate-500 text-sm mt-1">Configure and manage your clinic's specialized service offerings.</p>
        </div>

        <button @click="openAddService = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#003366] hover:bg-[#002244] text-white font-medium text-sm rounded-xl shadow-sm transition duration-150 active:scale-95">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Add New Service</span>
        </button>
    </div>

    <!-- Services Grid List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($services as $service)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                <div>
                    <!-- Card Top Line: Name & Status Badge -->
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <h2 class="font-bold text-slate-800 text-lg leading-snug">{{ $service->name }}</h2>
                        @if($service->status == 'on')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Disabled
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center gap-2 mt-2">
                    <button type="button" @click="openView(@js($service->name))"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium text-xs rounded-lg transition duration-150">
                        <i class="fa-solid fa-user-doctor text-xs"></i>
                        <span>View Doctors</span>
                    </button>

                    <button type="button" @click="openEditService = {{ $service->id }}"
                            class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-lg transition duration-150">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ================= ADD SERVICE MODAL ================= -->
    <div x-show="openAddService" x-cloak
         class="fixed inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-slate-100 relative"
             @click.away="openAddService = false">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h2 class="text-base font-bold text-[#003366]">Add New Service</h2>
                <button @click="openAddService = false" class="text-slate-400 hover:text-slate-600 transition text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.services.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Service Name</label>
                    <input type="text" name="name" placeholder="e.g. Pediatrics, Cardiology" 
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Operational Status</label>
                    <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]" required>
                        <option value="">Select Status</option>
                        <option value="on">Active (ON)</option>
                        <option value="off">Disabled (OFF)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openAddService = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-[#003366] hover:bg-[#002244] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        Save Service
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= EDIT SERVICE MODALS ================= -->
    @foreach($services as $service)
        <div x-show="openEditService === {{ $service->id }}" x-cloak
             class="fixed inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm z-50 p-4">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-slate-100 relative"
                 @click.away="openEditService = null">
                
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="text-base font-bold text-[#003366]">Edit Service</h2>
                    <button @click="openEditService = null" class="text-slate-400 hover:text-slate-600 transition text-lg">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <form method="POST" action="{{ route('admin.services.update', $service->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Service Name</label>
                            <input name="name" value="{{ $service->name }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Status</label>
                            <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]">
                                <option value="on" {{ $service->status == 'on' ? 'selected' : '' }}>Active (ON)</option>
                                <option value="off" {{ $service->status == 'off' ? 'selected' : '' }}>Disabled (OFF)</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-[#003366] hover:bg-[#002244] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                            Update Service
                        </button>
                    </form>

                    <div class="pt-3 border-t border-slate-100">
                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this service?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-2.5 bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-xl transition">
                                Delete Service
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @endforeach

    <!-- ================= VIEW DOCTORS MODAL ================= -->
    <div x-show="openViewDoctors" x-cloak
         class="fixed inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm z-50 p-4"
         @click.self="closeView()">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-slate-100 relative">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h2 class="text-base font-bold text-[#003366]">
                    Specialists: <span x-text="openViewDoctors" class="text-emerald-600"></span>
                </h2>
                <button @click="closeView()" class="text-slate-400 hover:text-slate-600 transition text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="space-y-2.5 max-h-72 overflow-y-auto">
                    <template x-for="doctor in getDoctors()" :key="doctor.id">
                        <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#003366]/10 text-[#003366] font-bold text-xs flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-user-doctor"></i>
                            </div>
                            <span class="text-sm font-semibold text-slate-800" x-text="doctor.name"></span>
                        </div>
                    </template>

                    <template x-if="getDoctors().length === 0">
                        <div class="text-center py-8 text-slate-400">
                            <i class="fa-regular fa-user-slash text-2xl mb-2 opacity-60"></i>
                            <p class="text-xs font-medium">No doctors currently assigned to this specialty.</p>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection