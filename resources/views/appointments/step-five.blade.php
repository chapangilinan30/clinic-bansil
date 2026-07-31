<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-[#2d5a71] mb-2">Review Summary</h1>
                <p class="text-gray-500">Please double-check your details before confirming.</p>
            </div>

            <div class="bg-white border-2 border-gray-100 rounded-[2rem] p-8 shadow-sm">
                {{-- Schedule Header --}}
                <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-gray-50">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-calendar-check text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Selected Schedule</p>
                        <p class="text-lg font-bold text-[#2d5a71]">
                            {{ \Carbon\Carbon::parse($selected_date)->format('F d, Y') }} at {{ $selected_time }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    {{-- Doctor Details --}}
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Specialist</p>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                                <i class="fa-solid fa-user-doctor"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Dr. {{ $selectedDoctor->name }}</p>
                                <p class="text-xs text-blue-600">{{ $selectedDepartment }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Patient Details --}}
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Patient Information</p>
                        <p class="font-bold text-gray-800">{{ $patient_name }}</p>
                        <p class="text-xs text-gray-500">{{ $patient_email }} | {{ $patient_phone }}</p>
                    </div>
                </div>

                @if($notes)
                <div class="mb-8 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Reason for Visit</p>
                    <p class="text-sm text-gray-600 italic">"{{ $notes }}"</p>
                </div>
                @endif

                {{-- FINAL FORM --}}
                <form action="{{ route('appointments.store') }}" method="POST">
                    @csrf
                    {{-- These hidden fields must match the validation in AppointmentController@store --}}
                    <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                    <input type="hidden" name="doctor_id" value="{{ $selectedDoctor->id }}">
                    <input type="hidden" name="appointment_date" value="{{ $selected_date }}">
                    <input type="hidden" name="appointment_time" value="{{ $selected_time }}">
                    <input type="hidden" name="patient_name" value="{{ $patient_name }}">
                    <input type="hidden" name="patient_email" value="{{ $patient_email }}">
                    <input type="hidden" name="patient_phone" value="{{ $patient_phone }}">
                    <input type="hidden" name="notes" value="{{ $notes }}">

                    <div class="flex flex-col space-y-3">
                        <button type="submit" class="w-full py-4 bg-[#3b82f6] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-600 transition transform hover:scale-[1.02]">
                            Confirm Appointment <i class="fa-solid fa-check-circle ml-2"></i>
                        </button>
                        {{-- Laravel's back() works here to return to step four --}}
                        <a href="{{ url()->previous() }}" class="text-center py-3 text-gray-400 font-bold hover:text-gray-600 transition text-sm">
                            Go Back & Edit
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>