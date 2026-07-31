@extends('layouts.doctor')

@section('content')

<h2 class="text-2xl font-bold mb-6 text-gray-800">Doctor Availability Settings</h2>

@if($errors->has('time_error'))
    <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
        {{ $errors->first('time_error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  <div class="lg:col-span-2">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h3 class="text-lg font-semibold text-gray-800">Weekly Availability</h3>
          <p class="text-xs text-gray-500">Create time blocks and apply them to multiple days of the week.</p>
        </div>
        <button type="button" onclick="addTimeBlock()" class="bg-indigo-50 border border-indigo-200 text-indigo-600 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-indigo-100 transition-colors flex items-center gap-1">
          ➕ Add Block
        </button>
      </div>

      <form id="scheduleForm" method="POST" action="{{ route('doctor.schedules.store') }}">
        @csrf
        <input type="hidden" name="type" value="weekly">

        <div id="timeBlocksContainer" class="space-y-4">
          <div class="time-block-card bg-gray-50 p-5 rounded-xl border border-gray-200 relative group transition-all">
            <button type="button" onclick="removeTimeBlock(this)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors text-sm font-medium hidden group-hover:block">
              🗑️ Remove
            </button>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">From</label>
                <input type="time" class="start-time-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" required>
              </div>
              <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">To</label>
                <input type="time" class="end-time-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" required>
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Applies to</label>
              <div class="flex flex-wrap gap-2">
                @foreach(['Monday' => 'M', 'Tuesday' => 'T', 'Wednesday' => 'W', 'Thursday' => 'Th', 'Friday' => 'F', 'Saturday' => 'Sa', 'Sunday' => 'Su'] as $fullName => $shortName)
                  <button type="button" 
                          onclick="toggleDayBadge(this, '{{ $fullName }}')" 
                          class="day-badge w-10 h-10 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm flex items-center justify-center hover:border-indigo-500 hover:text-indigo-600 transition-all select-none">
                    {{ $shortName }}
                  </button>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        <div id="hiddenSubmissionsContainer"></div>

        <button type="submit" class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-lg shadow-sm transition-colors">
          Save Weekly Schedule
        </button>
      </form>
    </div>
  </div>

  <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
    <h3 class="text-lg font-semibold text-gray-800 mb-1">Leave & Holidays</h3>
    <p class="text-xs text-gray-500 mb-4">Mark specific dates you are entirely unavailable.</p>

    <form method="POST" action="{{ route('doctor.unavailability.store') }}" class="flex gap-2 mb-4">
      @csrf
      <input type="date" name="date" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
      <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 rounded-lg font-medium transition-colors">
        Add
      </button>
    </form>

    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
      @forelse($unavailabilities ?? [] as $item)
        <div class="flex justify-between items-center bg-gray-50 border border-gray-100 px-3 py-2 rounded-lg">
          <span class="text-sm text-gray-700 font-medium">{{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</span>
          <form method="POST" action="{{ route('doctor.unavailability.destroy', $item->id) }}">
            @csrf
            @method('DELETE')
            <button class="text-gray-400 hover:text-red-500 font-bold transition-colors">✕</button>
          </form>
        </div>
      @empty
        <p class="text-sm text-gray-400 text-center py-4">No unavailable dates set.</p>
      @endforelse
    </div>
  </div>

</div>

<script>
// Toggle day badge state values
function toggleDayBadge(button, dayName) {
    if (button.dataset.selected === "true") {
        button.dataset.selected = "false";
        button.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-sm');
        button.classList.add('bg-white', 'text-gray-600', 'border-gray-300');
    } else {
        button.dataset.selected = "true";
        button.dataset.day = dayName;
        button.classList.remove('bg-white', 'text-gray-600', 'border-gray-300');
        button.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'shadow-sm');
    }
}

// Add another Time Card Block
function addTimeBlock() {
    const container = document.getElementById('timeBlocksContainer');
    const blueprint = `
      <div class="time-block-card bg-gray-50 p-5 rounded-xl border border-gray-200 relative group transition-all animate-fadeIn">
        <button type="button" onclick="removeTimeBlock(this)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors text-sm font-medium">
          🗑️ Remove
        </button>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">From</label>
            <input type="time" class="start-time-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" required>
          </div>
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">To</label>
            <input type="time" class="end-time-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" required>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Applies to</label>
          <div class="flex flex-wrap gap-2">
            <button type="button" onclick="toggleDayBadge(this, 'Monday')" class="day-badge w-10 h-10 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm flex items-center justify-center hover:border-indigo-500 hover:text-indigo-600 transition-all select-none">M</button>
            <button type="button" onclick="toggleDayBadge(this, 'Tuesday')" class="day-badge w-10 h-10 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm flex items-center justify-center hover:border-indigo-500 hover:text-indigo-600 transition-all select-none">T</button>
            <button type="button" onclick="toggleDayBadge(this, 'Wednesday')" class="day-badge w-10 h-10 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm flex items-center justify-center hover:border-indigo-500 hover:text-indigo-600 transition-all select-none">W</button>
            <button type="button" onclick="toggleDayBadge(this, 'Thursday')" class="day-badge w-10 h-10 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm flex items-center justify-center hover:border-indigo-500 hover:text-indigo-600 transition-all select-none">Th</button>
            <button type="button" onclick="toggleDayBadge(this, 'Friday')" class="day-badge w-10 h-10 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm flex items-center justify-center hover:border-indigo-500 hover:text-indigo-600 transition-all select-none">F</button>
            <button type="button" onclick="toggleDayBadge(this, 'Saturday')" class="day-badge w-10 h-10 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm flex items-center justify-center hover:border-indigo-500 hover:text-indigo-600 transition-all select-none">Sa</button>
            <button type="button" onclick="toggleDayBadge(this, 'Sunday')" class="day-badge w-10 h-10 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm flex items-center justify-center hover:border-indigo-500 hover:text-indigo-600 transition-all select-none">Su</button>
          </div>
        </div>
      </div>
    `;
    container.insertAdjacentHTML('beforeend', blueprint);
}

// Remove an added Card Block
function removeTimeBlock(button) {
    const cards = document.querySelectorAll('.time-block-card');
    if (cards.length > 1) {
        button.closest('.time-block-card').remove();
    } else {
        alert("You must keep at least one time block.");
    }
}

// Map frontend data to back-end compatible array schema before submit
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    const hiddenContainer = document.getElementById('hiddenSubmissionsContainer');
    hiddenContainer.innerHTML = ''; // clear out prior compiles

    const cards = document.querySelectorAll('.time-block-card');
    let dynamicInputHTML = '';
    let selectedAnyDay = false;

    cards.forEach(card => {
        const startTime = card.querySelector('.start-time-input').value;
        const endTime = card.querySelector('.end-time-input').value;
        const badges = card.querySelectorAll('.day-badge[data-selected="true"]');

        badges.forEach(badge => {
            const dayName = badge.dataset.day;
            selectedAnyDay = true;

            // Translate structure seamlessly into standard requests format:
            // days[DayName][active]=1, days[DayName][start_time]=XX, etc.
            dynamicInputHTML += `
                <input type="hidden" name="days[${dayName}][active]" value="1">
                <input type="hidden" name="days[${dayName}][start_time]" value="${startTime}">
                <input type="hidden" name="days[${dayName}][end_time]" value="${endTime}">
                <input type="hidden" name="days[${dayName}][slot_duration]" value="20">
            `;
        });
    });

    if (!selectedAnyDay) {
        e.preventDefault();
        alert('Please select at least one day for your time configurations.');
        return;
    }

    hiddenContainer.innerHTML = dynamicInputHTML;
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.2s ease-out forwards;
}
</style>
@endsection