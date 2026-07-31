@extends('layouts.doctor')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Top Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-[#003366] tracking-tight">
                Medicine Management
            </h2>
            <p class="text-xs text-[#7794a3] font-semibold uppercase tracking-wider mt-1.5">
                Manage and maintain the clinic's list of standard prescriptive medications.
            </p>
        </div>
    </div>

    {{-- Add Medicine Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-[#f4f7fc] flex items-center justify-between">
            <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-plus-circle text-[#1F6F8B] text-sm"></i>
                Add New Medicine
            </h3>
        </div>
        
        <div class="p-6 md:p-8">
            <form action="{{ route('doctor.medicines.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <div class="flex-1">
                    <input type="text" 
                           name="name"
                           placeholder="Enter medicine name (e.g., Amoxicillin 500mg)"
                           class="w-full bg-slate-50/30 border border-slate-200 rounded-xl px-4 py-3 text-sm text-[#003366] placeholder-slate-400 focus:border-[#1F6F8B] focus:ring-4 focus:ring-[#1F6F8B]/15 focus:outline-none transition-all shadow-xs" 
                           required>
                </div>

                {{-- Updated Add Button --}}
                <button type="submit" 
                        class="bg-[#1F6F8B] hover:bg-[#165267] active:bg-[#113f50] text-white px-7 py-3 text-xs font-bold uppercase tracking-wider rounded-xl active:scale-95 transition-all shadow-xs flex items-center justify-center gap-2 shrink-0">
                    <i class="fa-solid fa-plus text-xs"></i> Add Medicine
                </button>
            </form>
        </div>
    </div>

    {{-- Medicine List Table Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-[#f4f7fc]">
            <h3 class="text-xs font-bold text-[#003366] uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-capsules text-[#1F6F8B] text-sm"></i>
                Active Medicine List
            </h3>
            <span class="bg-[#1F6F8B]/10 text-[#1F6F8B] text-[10px] font-bold px-3 py-1 rounded-lg tracking-wider uppercase border border-[#1F6F8B]/20">
                Total: {{ $medicines->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[#7794a3] text-[10px] font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-4 px-6 md:px-8">Medicine Name</th>
                        <th class="py-4 px-6 md:px-8 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($medicines as $medicine)
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            
                            {{-- Medicine Name --}}
                            <td class="py-4 px-6 md:px-8 text-xs font-bold text-[#003366] group-hover:text-[#1F6F8B] transition-colors uppercase tracking-wider">
                                {{ $medicine->name }}
                            </td>
                            
                            {{-- Actions --}}
                            <td class="py-4 px-6 md:px-8 text-right">
                                <form action="{{ route('doctor.medicines.destroy', $medicine->id) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this medicine?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200/60 hover:border-transparent px-4 py-2 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all active:scale-95 shadow-xs">
                                        <i class="fa-solid fa-trash-can text-xs"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- Empty State --}}
                        <tr>
                            <td colspan="2" class="py-16 px-6 text-center bg-slate-50/20">
                                <div class="w-12 h-12 rounded-2xl bg-[#1F6F8B]/10 border border-[#1F6F8B]/20 flex items-center justify-center mx-auto mb-3 text-[#1F6F8B]">
                                    <i class="fa-solid fa-pills text-xl"></i>
                                </div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#003366] mb-1.5">
                                    No Medicines Added Yet
                                </h4>
                                <p class="text-xs text-[#7794a3] max-w-sm mx-auto leading-relaxed">
                                    Use the form above to expand your clinical prescription inventory.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection