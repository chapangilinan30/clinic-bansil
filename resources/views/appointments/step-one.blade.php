<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between mb-12 max-w-4xl mx-auto">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $i == 1 ? 'bg-[#7BA7B4] text-white' : 'bg-gray-200 text-gray-500' }}">
                            {{ $i }}
                        </div>
                        <span class="text-xs mt-2 {{ $i == 1 ? 'text-[#7BA7B4] font-bold' : 'text-gray-400' }}">Step {{ $i }}</span>
                    </div>
                @endfor
            </div>

            <h2 class="text-3xl font-bold text-[#2D5A71] text-center mb-2">Select Department</h2>
            <p class="text-gray-500 text-center mb-10">Choose the medical department for your concern.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $depts = [
                        ['id' => 'Pediatrician', 'name' => 'Pediatrician', 'icon' => 'fa-user-doctor'],
                        ['id' => 'OB-GYN', 'name' => 'OB-GYNE', 'icon' => 'fa-venus'],
                        ['id' => 'OB-SONO', 'name' => 'OB-SONO', 'icon' => 'fa-baby-carriage'],
                        ['id' => 'Midwife', 'name' => 'Midwife', 'icon' => 'fa-hands-holding-child'],
                    ];
                @endphp

                @foreach($depts as $dept)
                <a href="{{ route('appointments.step-two', ['department' => $dept['id']]) }}" 
                   class="bg-white border-2 border-transparent hover:border-[#7BA7B4] rounded-[40px] p-8 shadow-sm transition-all group text-center flex flex-col items-center">
                    
                    <div class="w-20 h-20 bg-cyan-50 text-[#7BA7B4] rounded-[24px] flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <i class="fa-solid {{ $dept['icon'] }} text-3xl"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold text-[#2D5A71]">{{ $dept['name'] }}</h3>
                    <p class="text-gray-400 text-sm mt-2 leading-relaxed">
                        Specialized care for {{ strtolower($dept['name']) }} concerns.
                    </p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>