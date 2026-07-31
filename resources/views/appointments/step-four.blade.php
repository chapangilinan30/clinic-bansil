<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            
            {{-- Stepper Progress --}}
            <div class="flex items-center justify-center mb-12 max-w-3xl mx-auto">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center border-2 border-blue-500 text-blue-500"><i class="fa-solid fa-check"></i></div>
                    <span class="text-xs mt-2 text-gray-400">Dept</span>
                </div>
                <div class="flex-1 h-1 bg-blue-500 mx-2"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center border-2 border-blue-500 text-blue-500"><i class="fa-solid fa-check"></i></div>
                    <span class="text-xs mt-2 text-gray-400">Doctor</span>
                </div>
                <div class="flex-1 h-1 bg-blue-500 mx-2"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center border-2 border-blue-500 text-blue-500"><i class="fa-solid fa-check"></i></div>
                    <span class="text-xs mt-2 text-gray-400">Schedule</span>
                </div>
                <div class="flex-1 h-1 bg-blue-500 mx-2"></div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">4</div>
                    <span class="text-xs mt-2 text-blue-600 font-bold">Information</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">5</div>
            </div>

            {{-- FORM START --}}
            <form action="{{ route('appointments.step-five') }}" method="POST">
                @csrf
                
                {{-- Passing data from previous steps --}}
                {{-- FIX: Ginagamit natin ang variables na galing sa Controller --}}
                <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                <input type="hidden" name="doctor" value="{{ $selectedDoctor }}">
                <input type="hidden" name="selected_date" value="{{ $selected_date }}">
                <input type="hidden" name="selected_time" value="{{ $selected_time }}">

                <div class="bg-white rounded-[40px] p-10 shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-[#2D5A71] mb-8 font-serif-medical">Patient Details</h2>

                    <div class="grid grid-cols-1 gap-6">
                        {{-- Patient Name --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
                            <input type="text" name="patient_name" value="{{ auth()->user()->name }}" required
                                class="w-full p-5 bg-gray-50 border-none rounded-2xl text-lg focus:ring-2 focus:ring-blue-400 transition-all"
                                placeholder="Enter patient's full name">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Phone Number --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Phone Number</label>
                                <input type="tel" name="patient_phone" required
                                    class="w-full p-5 bg-gray-50 border-none rounded-2xl text-lg focus:ring-2 focus:ring-blue-400 transition-all"
                                    placeholder="09XXXXXXXXX">
                            </div>

                            {{-- Email Address --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                                <input type="email" name="patient_email" value="{{ auth()->user()->email }}" required
                                    class="w-full p-5 bg-gray-50 border-none rounded-2xl text-lg focus:ring-2 focus:ring-blue-400 transition-all"
                                    placeholder="example@email.com">
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Reason for Visit (Optional)</label>
                            <textarea name="notes" rows="3" 
                                class="w-full p-5 bg-gray-50 border-none rounded-2xl text-lg focus:ring-2 focus:ring-blue-400 transition-all"
                                placeholder="Tell us more about your concern..."></textarea>
                        </div>
                    </div>

                    {{-- Navigation --}}
                    <div class="flex justify-between items-center mt-12 pt-8 border-t border-gray-50">
                        <a href="{{ url()->previous() }}" class="text-gray-400 font-bold hover:text-gray-600 transition">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Back
                        </a>
                        <button type="submit" class="bg-[#7BA7B4] text-white px-12 py-4 rounded-2xl font-bold shadow-lg shadow-cyan-100 hover:scale-105 transition-transform">
                            Review Summary <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>