<x-app-layout>
    <div class="min-h-screen bg-slate-50">

        <!-- ================= HEADER ================= -->
        <header class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-8 py-5 flex justify-between items-center">
                
                <!-- CLINIC BRAND -->
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xl">
                        CB
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-slate-800 leading-tight">
                            Clinica Bansil
                        </h1>
                        <p class="text-xs text-slate-500 tracking-wide uppercase">
                            Smart Healthcare System
                        </p>
                    </div>
                </div>

                <!-- DOCTOR INFO -->
                <div class="text-right">
                    <p class="text-sm font-semibold text-slate-700">
                        Dr. Paul Anthony Bansil
                    </p>
                    <p class="text-xs text-slate-500">
                        Pediatrician
                    </p>
                </div>

            </div>
        </header>

        <!-- ================= CONTENT ================= -->
        <main class="max-w-7xl mx-auto px-8 py-12">

            <!-- PAGE TITLE -->
            <div class="mb-10">
                <h2 class="text-3xl font-semibold text-slate-800">
                    Patient Dashboard
                </h2>
                <p class="text-slate-500 mt-1">
                    Manage your appointments and clinic visits
                </p>
            </div>

            <!-- ================= PATIENT CARD ================= -->
            <section class="bg-white rounded-2xl shadow-sm border p-8 mb-14 flex justify-between items-center">
                
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd">
                            </path>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-slate-800">
                            {{ Auth::user()->name }}
                        </h3>
                        <p class="text-sm text-blue-600">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>

                <!-- ACTION ICONS -->
                <div class="flex items-center gap-5">
                    <button class="text-slate-500 hover:text-blue-600 transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>

                    <button class="text-slate-500 hover:text-blue-600 transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                                   a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                                   C7.67 6.165 6 8.388 6 11v3.159
                                   c0 .538-.214 1.055-.595 1.436L4 17h5
                                   m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-600 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1
                                       a3 3 0 01-3 3H6a3 3 0 01-3-3V7
                                       a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </section>

            <!-- ================= ACTION CARDS ================= -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-10">

                <!-- BOOK APPOINTMENT -->
                <a href="{{ route('appointments.step-one') }}"
                   class="bg-blue-600 hover:bg-blue-700 transition
                          rounded-2xl shadow-md p-12 flex items-center justify-center">
                    <span class="text-white text-2xl font-semibold">
                        Book Appointment
                    </span>
                </a>

                <!-- QUEUE STATUS -->
                <a href="{{ route('appointments.index') }}"
                   class="bg-white border hover:border-blue-600 transition
                          rounded-2xl shadow-sm p-12 flex items-center justify-center">
                    <span class="text-slate-700 text-2xl font-semibold">
                        Check Queue Status
                    </span>
                </a>

            </section>
        </main>
    </div>
</x-app-layout>