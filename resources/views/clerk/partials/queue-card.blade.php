<div class="flex items-center gap-5 p-5 border-2 rounded-[1.5rem] bg-white transition-all shadow-sm mb-4
    {{ $app->status == 'checked-in' ? 'border-green-200' : ($app->status == 'no-show' ? 'border-red-200' : 'border-gray-100') }}">
    
    <div class="w-16 h-16 flex items-center justify-center rounded-2xl text-3xl font-black 
        {{ $app->status == 'checked-in' ? 'bg-green-100 text-green-600' : ($app->status == 'no-show' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400') }}">
        {{ $number }}
    </div>

    <div class="flex-1">
        <div class="flex justify-between items-start">
            <h4 class="font-black text-xl text-gray-800">{{ $app->patient_name }}</h4>
            <span class="px-4 py-1 rounded-full text-[10px] font-black uppercase 
                {{ $app->status == 'checked-in' ? 'bg-green-100 text-green-600' : ($app->status == 'no-show' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400') }}">
                {{ $app->status }}
            </span>
        </div>
        <div class="flex gap-4 mt-1 text-sm font-bold text-gray-400">
            <span>{{ $app->appointment_time }}</span>
            <span class="text-[#7BA7B4]">{{ $app->doctor_name ?? 'Dr. Bansil' }}</span>
        </div>
    </div>
    <div class="text-gray-300 px-2"><i class="fa-solid fa-chevron-right text-xl"></i></div>
</div>