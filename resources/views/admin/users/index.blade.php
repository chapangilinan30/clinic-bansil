@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
{{-- Hide x-cloak elements before Alpine initializes to prevent flash of content --}}
<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="{
    openAdd: false,
    openEdit: false,
    editUser: {},
    roleSelect: 'admin',
    activeTab: 'admin',
    activeDropdown: null,
    
    toggleDropdown(id) {
        this.activeDropdown = this.activeDropdown === id ? null : id;
    }
}" @click.away="activeDropdown = null">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#003366]">User Management</h1>
            <p class="text-slate-500 text-sm mt-1">Manage system accounts, staff roles, and administrative access permissions.</p>
        </div>

        <button @click="openAdd = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#003366] hover:bg-[#002244] text-white font-medium text-sm rounded-xl shadow-sm transition duration-150 active:scale-95">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Add New User</span>
        </button>
    </div>

    <!-- Role Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 mb-6 overflow-x-auto">
        @foreach(['admin', 'doctor', 'clerk', 'staff'] as $role)
            @php
                $count = $users->where('role', $role)->count();
            @endphp
            <button @click="activeTab = '{{ $role }}'"
                    :class="activeTab === '{{ $role }}' ? 'border-[#003366] text-[#003366] font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'"
                    class="py-3 px-4 border-b-2 text-sm capitalize whitespace-nowrap flex items-center gap-2 transition duration-150">
                <span>{{ $role }}s</span>
                <span :class="activeTab === '{{ $role }}' ? 'bg-[#003366]/10 text-[#003366]' : 'bg-slate-100 text-slate-500'"
                      class="px-2 py-0.5 text-xs rounded-full font-semibold">
                    {{ $count }}
                </span>
            </button>
        @endforeach
    </div>

    <!-- Role Tables Container -->
    @foreach(['admin', 'doctor', 'clerk', 'staff'] as $role)
        <div x-show="activeTab === '{{ $role }}'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200/80">
                                <th class="py-4 px-6">User Info</th>
                                <th class="py-4 px-6">Email Address</th>
                                @if($role === 'doctor')
                                    <th class="py-4 px-6">Specialization</th>
                                @endif
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($users->where('role', $role) as $user)
                                <tr class="hover:bg-slate-50/60 transition duration-150">
                                    
                                    <!-- User Info -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-[#003366]/10 text-[#003366] font-bold text-sm flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800 text-base leading-tight flex items-center gap-2">
                                                    <span>{{ $user->name }}</span>
                                                    @if($user->is_admin)
                                                        <span class="px-2 py-0.5 bg-sky-100 text-sky-800 text-[10px] font-bold rounded-full border border-sky-200 uppercase tracking-wider">
                                                            Super
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-slate-400 mt-0.5 capitalize">{{ $user->role }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Email -->
                                    <td class="py-4 px-6 text-slate-600 font-medium">
                                        {{ $user->email }}
                                    </td>

                                    <!-- Specialization (Only for Doctors) -->
                                    @if($role === 'doctor')
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                                {{ $user->specialization ?? 'General' }}
                                            </span>
                                        </td>
                                    @endif

                                    <!-- Actions Menu -->
                                    <td class="py-4 px-6 text-right relative">
                                        <button @click.stop="toggleDropdown({{ $user->id }})" 
                                                class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition inline-flex items-center justify-center">
                                            <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="activeDropdown === {{ $user->id }}" x-cloak
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             class="absolute right-6 top-12 bg-white rounded-xl shadow-lg border border-slate-100 z-50 w-36 py-1.5 text-left">
                                            
                                            <button @click="openEdit = true; editUser = {{ json_encode($user) }}; activeDropdown = null;"
                                                    class="w-full px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition">
                                                <i class="fa-solid fa-pen-to-square text-slate-400"></i>
                                                <span>Edit</span>
                                            </button>

                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition">
                                                    <i class="fa-solid fa-trash-can text-rose-400"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $role === 'doctor' ? 4 : 3 }}" class="text-center py-12 text-slate-400">
                                        <i class="fa-regular fa-folder-closed text-3xl mb-2 opacity-60"></i>
                                        <p class="text-sm font-medium">No {{ $role }} accounts registered yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endforeach

    <!-- ================= ADD USER MODAL ================= -->
    <div x-show="openAdd" x-cloak
         class="fixed inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-slate-100 relative"
             @click.away="openAdd = false">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h2 class="text-base font-bold text-[#003366] flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    Add New User Account
                </h2>
                <button @click="openAdd = false" class="text-slate-400 hover:text-slate-600 transition text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Full Name</label>
                    <input name="name" type="text" placeholder="John Doe" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Email Address</label>
                    <input name="email" type="email" placeholder="user@clinic.com" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Password</label>
                    <input name="password" type="password" placeholder="••••••••" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Role</label>
                    <select name="role" x-model="roleSelect" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]">
                        <option value="admin">Admin</option>
                        <option value="doctor">Doctor</option>
                        <option value="clerk">Clerk</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <!-- Conditional Specialization for Doctors -->
                <div x-show="roleSelect === 'doctor'" x-cloak>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Specialization</label>
                    <select name="specialization" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]">
                        <option value="Pediatrician">Pediatrician</option>
                        <option value="Ob-Gyne">Ob-Gyne</option>
                        <option value="Midwife">Midwife</option>
                        <option value="Ob-Sono">Ob-Sono</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openAdd = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-[#003366] hover:bg-[#002244] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= EDIT USER MODAL ================= -->
    <div x-show="openEdit" x-cloak
         class="fixed inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-slate-100 relative"
             @click.away="openEdit = false">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h2 class="text-base font-bold text-[#003366] flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-xs"></i>
                    Edit User Profile
                </h2>
                <button @click="openEdit = false" class="text-slate-400 hover:text-slate-600 transition text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="`/admin/users/${editUser.id}`" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Full Name</label>
                    <input x-model="editUser.name" name="name" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Email Address</label>
                    <input x-model="editUser.email" name="email" type="email" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Role</label>
                    <select x-model="editUser.role" name="role" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]">
                        <option value="admin">Admin</option>
                        <option value="doctor">Doctor</option>
                        <option value="clerk">Clerk</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div x-show="editUser.role === 'doctor'" x-cloak>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Specialization</label>
                    <input x-model="editUser.specialization" name="specialization" placeholder="Doctor Specialization" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366]">
                </div>

                <div class="pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_admin" :checked="editUser.is_admin == 1" value="1" class="rounded border-slate-300 text-[#003366] focus:ring-[#003366]/20 w-4 h-4">
                        <span class="text-xs font-semibold text-slate-700">Grant Super-Admin Privilege</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openEdit = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-[#003366] hover:bg-[#002244] text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection