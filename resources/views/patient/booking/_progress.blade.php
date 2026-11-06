@php
    $steps = ['Department', 'Doctor', 'Date & Time', 'Information', 'Confirm'];
@endphp

<section class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-6" aria-label="Booking progress">
    <ol class="grid grid-cols-5 gap-1 sm:gap-2">
        @foreach($steps as $index => $label)
            @php($stepNumber = $index + 1)
            <li
                @if($currentStep === $stepNumber) aria-current="step" @endif
                aria-label="Step {{ $stepNumber }}: {{ $label }}"
                class="flex min-w-0 flex-col items-center gap-1.5 text-center text-[10px] font-semibold sm:text-xs {{ $stepNumber <= $currentStep ? 'text-[#1F6F8B]' : 'text-slate-400' }}"
            >
                <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs font-bold sm:hidden {{ $stepNumber <= $currentStep ? 'border-[#1F6F8B] bg-[#1F6F8B] text-white' : 'border-slate-300 bg-white text-slate-500' }}">
                    {{ $stepNumber }}
                </span>
                <span class="hidden sm:block">{{ $stepNumber }}. {{ $label }}</span>
            </li>
        @endforeach
    </ol>
    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100" aria-hidden="true">
        <div class="h-full rounded-full bg-[#1F6F8B] transition-all duration-300" style="width: {{ $currentStep * 20 }}%"></div>
    </div>
</section>
