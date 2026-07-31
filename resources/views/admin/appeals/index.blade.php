@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-[#003366]">
            Booking Appeals Workspace
        </h1>
        <p class="text-slate-500 text-sm mt-1">
            Review and manage patient requests to lift account lockouts and restore booking privileges.
        </p>
    </div>

    <!-- Quick Stats Pill -->
    <div class="flex items-center gap-3">
        <div class="bg-white border border-slate-200 shadow-sm rounded-xl px-4 py-2 flex items-center gap-3">
            <div class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></div>
            <div>
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Pending Review</span>
                <span class="text-sm font-bold text-[#003366]">{{ $appeals->count() }} Request{{ $appeals->count() == 1 ? '' : 's' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Session Alerts -->
@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm text-sm flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm text-sm flex items-center gap-3">
        <i class="fa-solid fa-circle-xmark text-rose-600 text-base"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Appeals Table Container -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

    @if($appeals->isEmpty())
        <!-- Modern Empty State -->
        <div class="p-12 text-center flex flex-col items-center justify-center">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                <i class="fa-regular fa-folder-closed text-2xl"></i>
            </div>
            <h3 class="text-slate-700 font-bold text-base">No Appeals Requiring Oversight</h3>
            <p class="text-slate-400 text-sm mt-1 max-w-sm">
                There are currently no pending reactivation requests from locked patient accounts.
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-4 px-6">Patient Info</th>
                        <th class="py-4 px-6">Stated Justification</th>
                        <th class="py-4 px-6">Submission Date</th>
                        <th class="py-4 px-6 text-center">Administrative Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($appeals as $appeal)
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            
                            <!-- Patient Information -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#003366]/10 text-[#003366] font-bold text-sm flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($appeal->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 text-base leading-tight">{{ $appeal->name }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                            <i class="fa-regular fa-envelope text-[11px]"></i>
                                            {{ $appeal->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Justification Reason -->
                            <td class="py-4 px-6 max-w-md">
                                <div class="bg-slate-50 border border-slate-100 p-3 rounded-xl text-slate-600 text-xs italic leading-relaxed">
                                    "{{ $appeal->reactivation_reason }}"
                                </div>
                            </td>

                            <!-- Date Submitted -->
                            <td class="py-4 px-6 text-slate-500 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                    <i class="fa-regular fa-clock text-slate-400"></i>
                                    {{ $appeal->updated_at ? $appeal->updated_at->format('M d, Y') : 'N/A' }}
                                </div>
                                <div class="text-[11px] text-slate-400 pl-5">
                                    {{ $appeal->updated_at ? $appeal->updated_at->format('h:i A') : '' }}
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    <!-- Approve Request -->
                                    <form action="{{ route('admin.appeals.approve', $appeal->id) }}" method="POST" onsubmit="return confirm('Restore booking privileges for this user?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-3.5 rounded-lg text-xs transition duration-150 shadow-sm active:scale-95">
                                            <i class="fa-solid fa-check text-xs"></i>
                                            Approve & Unlock
                                        </button>
                                    </form>

                                    <!-- Deny Request -->
                                    <form action="{{ route('admin.appeals.deny', $appeal->id) }}" method="POST" onsubmit="return confirm('Reject this reactivation appeal?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-700 font-medium py-2 px-3.5 rounded-lg text-xs transition duration-150 active:scale-95">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                            Deny
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

@endsection