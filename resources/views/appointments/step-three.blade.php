<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .font-serif-medical { font-family: 'Lora', serif; }
    .step-circle { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #cbd5e1; background: white; color: #64748b; font-weight: bold; }
    .step-active { background: #3b82f6; color: white; border-color: #3b82f6; }
    .step-completed { background: #e6f7fb; color: #3b82f6; border-color: #3b82f6; }
    .step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 12px; }
    
    /* Interactive Selection Styles */
    .date-card.selected { border-color: #3b82f6 !important; background-color: #eff6ff !important; box-shadow: 0 0 0 2px #3b82f6; }
    .time-slot.selected { background-color: #3b82f6 !important; color: white !important; border-color: #3b82f6 !important; }
</style>

<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10">
                <h1 class="text-4xl font-serif-medical text-[#2d5a71] mb-2">Select Date & Time</h1>
                <p class="text-gray-500 text-lg uppercase tracking-wide">Booking with <span class="text-blue-600 font-bold"> {{ $selectedDoctor->name }}</span></p>
            </div>

            {{-- Stepper Progress --}}
            <div class="flex items-center justify-center mb-16 max-w-3xl mx-auto px-4">
                <div class="flex flex-col items-center">
                    <div class="step-circle step-completed"><i class="fa-solid fa-check"></i></div>
                    <span class="text-xs mt-2 text-gray-400 font-bold">Dept</span>
                </div>
                <div class="step-line !bg-blue-500"></div>
                <div class="flex flex-col items-center">
                    <div class="step-circle step-completed"><i class="fa-solid fa-check"></i></div>
                    <span class="text-xs mt-2 text-gray-400 font-bold">Doctor</span>
                </div>
                <div class="step-line !bg-blue-500"></div>
                <div class="flex flex-col items-center">
                    <div class="step-circle step-active">3</div>
                    <span class="text-xs mt-2 text-blue-600 font-bold">Schedule</span>
                </div>
                <div class="step-line"></div>
                <div class="step-circle">4</div>
                <div class="step-line"></div>
                <div class="step-circle">5</div>
            </div>

            {{-- FORM START --}}
            <form action="{{ route('appointments.step-four') }}" method="GET" id="schedule-form">
                {{-- MAHALAGA: Hidden inputs para sa Department at Doctor ID --}}
                <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                <input type="hidden" name="doctor" value="{{ $selectedDoctor->id }}">
                
                {{-- Data mula sa JS selection --}}
                <input type="hidden" name="selected_date" id="input-date" required>
                <input type="hidden" name="selected_time" id="input-time" required>

                <div class="mb-10">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 font-serif-medical">Select Date</h3>
                    <div class="flex space-x-4 overflow-x-auto pb-4 scrollbar-hide">
                        @php
                            // Set these to the current week dates for March 2026
                            $days = [
                                ['day' => 'Mon', 'date' => '02', 'full' => '2026-03-02'],
                                ['day' => 'Tue', 'date' => '03', 'full' => '2026-03-03'],
                                ['day' => 'Wed', 'date' => '04', 'full' => '2026-03-04'],
                                ['day' => 'Thu', 'date' => '05', 'full' => '2026-03-05'],
                                ['day' => 'Fri', 'date' => '06', 'full' => '2026-03-06'],
                            ];
                        @endphp

                        @foreach($days as $d)
                        <button type="button" 
                                onclick="selectDate(this, '{{ $d['full'] }}')"
                                class="date-card group flex-shrink-0 w-24 p-5 border-2 border-gray-100 rounded-3xl transition-all hover:border-blue-300">
                            <div class="text-center w-full pointer-events-none">
                                <span class="block text-xs uppercase font-bold text-gray-400 mb-1">{{ $d['day'] }}</span>
                                <span class="block text-2xl font-black text-gray-800">{{ $d['date'] }}</span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="mb-12">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 font-serif-medical">Available Time Slots</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $slots = ['09:00 AM', '10:00 AM', '11:00 AM', '01:00 PM', '02:00 PM', '03:00 PM'];
                        @endphp

                        @foreach($slots as $slot)
                        <button type="button" 
                                onclick="selectTime(this, '{{ $slot }}')"
                                class="time-slot p-4 border-2 border-gray-100 rounded-2xl text-center font-bold text-gray-700 transition-all hover:border-blue-300">
                            {{ $slot }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-between items-center mt-16 pt-8 border-t border-gray-100">
                    <a href="{{ route('appointments.step-two', ['department' => $selectedDepartment]) }}" class="px-12 py-4 border-2 border-gray-300 rounded-2xl font-bold text-gray-600 hover:bg-gray-50 transition text-lg">
                       <i class="fa-solid fa-arrow-left mr-2"></i> Back
                    </a>
                    <button type="submit" class="px-14 py-4 bg-[#3b82f6] text-white rounded-2xl font-bold shadow-lg text-lg hover:bg-blue-600 transition disabled:opacity-50" id="continue-btn">
                        Continue <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectDate(element, dateValue) {
            document.querySelectorAll('.date-card').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('input-date').value = dateValue;
            checkValidation();
        }

        function selectTime(element, timeValue) {
            document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('input-time').value = timeValue;
            checkValidation();
        }

        function checkValidation() {
            const date = document.getElementById('input-date').value;
            const time = document.getElementById('input-time').value;
            const btn = document.getElementById('continue-btn');
            
            if(date && time) {
                btn.removeAttribute('disabled');
            }
        }

        document.getElementById('continue-btn').setAttribute('disabled', 'true');
    </script>
</x-app-layout>