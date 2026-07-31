<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Section --}}
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h1 class="text-4xl font-bold text-[#2D5A71] font-serif-medical">My Appointments</h1>
                    <p class="text-gray-500 mt-2">Track your current queue and history.</p>
                </div>
                <a href="{{ route('appointments.create') }}" class="bg-[#7BA7B4] text-white px-8 py-3 rounded-2xl font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> New Booking
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center shadow-sm">
                    <i class="fa-solid fa-circle-check mr-3 text-xl"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- SINGLE QUEUE CARD ONLY --}}
            @if(isset($appointment) && $appointment)
                <div class="flex justify-center mb-10">
                    <div class="bg-white p-8 rounded-[40px] border-2 border-[#7BA7B4] shadow-md text-center w-full max-w-sm">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Your Queue No.</p>
                        <h3 class="text-6xl font-black text-[#2D5A71]">#{{ $appointment->queue_number }}</h3>
                        <p class="text-sm text-gray-500 mt-3 italic">Please wait for your turn</p>
                    </div>
                </div>
            @endif

            {{-- APPOINTMENT LIST / HISTORY --}}
            <div class="grid grid-cols-1 gap-8">
                {{-- Dito nakalagay ang forelse loop mo para sa listahan... --}}
                @forelse($appointments as $app)
                    {{-- Parehong code ng listahan na ginamit natin kanina --}}
                @empty
                    {{-- Empty state... --}}
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>