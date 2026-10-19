@extends('layouts.admin')

@section('title', 'Automated Lockout Management')

@section('content')

<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-[#003366]">
            Automated Lockout Management
        </h1>
        <p class="text-slate-500 text-sm mt-1">
            Patient account lockouts are handled automatically by the system. Manual appeal oversight is disabled.
        </p>
    </div>
</div>

<!-- Blocked Users Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="font-bold text-slate-800">Currently Blocked Patients</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="text-left px-6 py-3">Name</th>
                    <th class="text-left px-6 py-3">Email</th>
                    <th class="text-left px-6 py-3">Unblocks At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($blockedUsers as $user)
                    <tr>
                        <td class="px-6 py-3 font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $user->unblocked_at ? \Carbon\Carbon::parse($user->unblocked_at)->format('M d, Y h:i A') : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-400">No blocked patients.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Informational Card -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center flex flex-col items-center justify-center">
    <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mb-4">
        <i class="fa-solid fa-robot"></i>
    </div>
    <h3 class="text-slate-800 font-bold text-lg">Auto-Unblock System Active</h3>
    <p class="text-slate-500 text-sm mt-2 max-w-md">
        Locked patient accounts will automatically regain booking access once their temporary restriction period expires. No admin review or manual approval is required.
    </p>
</div>

@endsection