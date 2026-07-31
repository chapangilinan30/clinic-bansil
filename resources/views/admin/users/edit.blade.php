@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <!-- Page Header & Back Navigation -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#003366] transition-colors mb-3">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            Back to Users Management
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-[#003366]">Edit User Profile</h1>
        <p class="text-slate-500 text-sm mt-1">Update administrative credentials and manage system access permissions.</p>
    </div>

    <!-- Main Card Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- User Avatar Summary Banner -->
        <div class="px-6 py-4 bg-slate-50/70 border-b border-slate-100 flex items-center gap-3">
            <div class="w-11 h-11 rounded-full bg-[#003366]/10 text-[#003366] font-bold text-base flex items-center justify-center shrink-0 border border-[#003366]/20">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <h3 class="font-semibold text-slate-800 text-sm leading-tight">{{ $user->name }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ $user->email }}</p>
            </div>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Full Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-user text-sm"></i>
                    </div>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366] transition" 
                           placeholder="John Doe" required>
                </div>
            </div>

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-regular fa-envelope text-sm"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366] transition" 
                           placeholder="user@clinic.com" required>
                </div>
            </div>

            <!-- Role Assignment -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Role Assignment</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                    </div>
                    <select name="role" class="w-full border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366] transition bg-white">
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="doctor" {{ $user->role == 'doctor' ? 'selected' : '' }}>Doctor</option>
                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff Member</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition duration-150">
                    Cancel
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#003366] hover:bg-[#002244] text-white font-semibold text-xs rounded-xl shadow-sm transition duration-150 active:scale-95">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection