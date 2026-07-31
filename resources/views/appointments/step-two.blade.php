<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .font-serif-medical { font-family: 'Lora', serif; }
    .step-circle { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #cbd5e1; background: white; color: #64748b; font-weight: bold; font-size: 1.1rem; }
    .step-active { background: #3b82f6; color: white; border-color: #3b82f6; box-shadow: 0 0 10px rgba(59, 130, 246, 0.3); }
    .step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 12px; }
    .step-completed { background: #e6f7fb; color: #3b82f6; border-color: #3b82f6; }
</style>

<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10">
                <h1 class="text-4xl font-serif-medical text-[#2d5a71] mb-2">
                    Select {{ $selectedDepartment === 'Midwife' ? 'Midwife' : 'Doctor' }}
                </h1>
                <p class="text-gray-500 text-lg">Available specialists for <span class="text-blue-600 font-bold uppercase">{{ str_replace('-', ' ', $selectedDepartment) }}</span></p>
            </div>

            {{-- Stepper Progress --}}
            <div class="flex items-center justify-center mb-16 max-w-3xl mx-auto px-4">
                <div class="flex flex-col items-center">
                    <div class="step-circle step-completed"><i class="fa-solid fa-check text-blue-500"></i></div>
                    <span class="text-xs mt-2 text-gray-400">Department</span>
                </div>
                <div class="step-line border-blue-500"></div>
                <div class="flex flex-col items-center">
                    <div class="step-circle step-active">2</div>
                    <span class="text-xs mt-2 text-blue-600 font-bold">Specialist</span>
                </div>
                <div class="step-line"></div>
                <div class="step-circle">3</div>
                <div class="step-line"></div>
                <div class="step-circle">4</div>
                <div class="step-line"></div>
                <div class="step-circle">5</div>
            </div>

            <div class="space-y-6 mb-12">
                @forelse($doctors as $doc)
                <div class="bg-white border-2 border-gray-100 rounded-[32px] p-6 shadow-sm hover:border-blue-400 transition-all flex flex-col md:flex-row items-center justify-between gap-6">
                    
                    <div class="flex items-center space-x-6">
                        <div class="w-24 h-24 bg-[#e6f7fb] rounded-2xl overflow-hidden flex items-center justify-center flex-shrink-0">
                            @if($selectedDepartment === 'Midwife')
                                <i class="fa-solid fa-hands-holding-child text-5xl text-[#2d5a71]"></i>
                            @else
                                <i class="fa-solid fa-user-doctor text-5xl text-[#2d5a71]"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 font-serif-medical">
                                {{ $selectedDepartment === 'Midwife' ? '' : '' }} {{ $doc->name }}
                            </h3>
                            <p class="text-blue-500 font-medium flex items-center">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                Available Today
                            </p>
                            <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">{{ $doc->specialization }}</p>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0">
                        {{-- FIXED: Inayos ang route name para sa Step 3 --}}
                        <a href="{{ route('appointments.step-three', ['department' => $selectedDepartment, 'doctor' => $doc->id]) }}" 
                           class="inline-block px-10 py-4 bg-[#3b82f6] text-white text-center rounded-2xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-600 active:transform active:scale-95 transition-all">
                            Select {{ $selectedDepartment === 'Midwife' ? 'Midwife' : 'Doctor' }}
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-gray-50 rounded-[32px] border-2 border-dashed border-gray-200">
                    <i class="fa-solid fa-user-slash text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-xl font-medium">No specialists available for {{ str_replace('-', ' ', $selectedDepartment) }} yet.</p>
                </div>
                @endforelse
            </div>

            <div class="flex justify-between items-center mt-16 pt-8 border-t border-gray-100">
                {{-- FIXED: Pinalitan ang 'appointments.step-one' ng 'appointments.create' --}}
                <a href="{{ route('appointments.create') }}" class="px-12 py-4 border-2 border-gray-300 rounded-2xl font-bold text-gray-600 hover:bg-gray-50 transition text-lg flex items-center">
                   <i class="fa-solid fa-arrow-left mr-2"></i> Back
                </a>
            </div>

        </div>
    </div>
</x-app-layout>