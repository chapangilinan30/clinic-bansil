@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Header Section -->
<div class="mb-6 sm:mb-8">
    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#003366]">Clinic Dashboard</h1>
    <p class="text-slate-500 text-xs sm:text-sm mt-1">Real-time system overview and booking activity highlights.</p>
</div>

<!-- MOBILE VIEW TAB SWITCHER (Visible ONLY on mobile/tablet screens < lg) -->
<div class="flex lg:hidden bg-slate-200/60 p-1 rounded-xl mb-6 text-xs font-semibold">
    <button id="btn-overview" onclick="switchTab('overview')" class="flex-1 py-2 rounded-lg bg-white text-[#003366] shadow-sm transition">
        Overview & Trends
    </button>
    <button id="btn-activity" onclick="switchTab('activity')" class="flex-1 py-2 rounded-lg text-slate-600 transition">
        Recent Bookings
    </button>
</div>

<!-- STATS CARDS GRID (Part of Tab 1 on Mobile, Always Visible on Desktop) -->
<div id="section-stats" class="block">
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 mb-6 lg:mb-8">
        @php
            $stats = [
                ['label' => 'Total Users', 'value' => $totalUsers ?? 0, 'icon' => 'fa-users', 'bg' => 'bg-blue-50 text-blue-600'],
                ['label' => 'Active Doctors', 'value' => $totalDoctors ?? 0, 'icon' => 'fa-user-doctor', 'bg' => 'bg-emerald-50 text-emerald-600'],
                ['label' => 'Active Clerks', 'value' => $totalStaff ?? 0, 'icon' => 'fa-clipboard-user', 'bg' => 'bg-purple-50 text-purple-600'],
                ['label' => 'Total Bookings', 'value' => isset($data) ? array_sum($data) : 0, 'icon' => 'fa-calendar-check', 'bg' => 'bg-amber-50 text-amber-600'],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center justify-between transition hover:shadow-md">
                <div>
                    <span class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">{{ $stat['label'] }}</span>
                    <span class="text-xl sm:text-2xl font-bold text-[#003366] tracking-tight">{{ number_format($stat['value']) }}</span>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl {{ $stat['bg'] }} flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $stat['icon'] }} text-sm sm:text-lg"></i>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- MAIN CONTENT GRID (Restored original desktop grid layout) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- System Activity Chart (Spans 2 columns on desktop) -->
    <div id="section-chart" class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 flex flex-col justify-between block">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <div>
                <h2 class="font-bold text-slate-800 text-base sm:text-lg">Booking Trends</h2>
                <p class="text-xs text-slate-400 mt-0.5">Patient appointment activity over time</p>
            </div>
            <span class="text-[10px] sm:text-xs font-semibold text-[#003366] bg-[#003366]/5 px-2.5 py-1 rounded-full border border-[#003366]/10">
                Live Data
            </span>
        </div>
        
        <div class="h-64 sm:h-80 w-full">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    <!-- Recent Activity Stream (1 column on desktop) -->
    <div id="section-activity" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 flex-col hidden lg:flex">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-slate-800 text-base sm:text-lg">Recent Bookings</h2>
            <i class="fa-solid fa-clock-rotate-left text-slate-400 text-sm"></i>
        </div>

        <div class="space-y-3 sm:space-y-4 overflow-y-auto max-h-[340px] pr-1">
            @forelse($activities as $activity)
                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100/60 transition duration-150">
                    <div class="w-8 h-8 rounded-full bg-[#003366]/10 text-[#003366] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        {{ strtoupper(substr($activity->patient_name ?? 'P', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-800 truncate">
                            {{ $activity->patient_name ?? 'Unknown Patient' }}
                        </p>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            Booked an appointment
                        </p>
                        <span class="text-[10px] text-slate-400 block mt-1">
                            <i class="fa-regular fa-calendar text-[9px] mr-1"></i>
                            {{ \Carbon\Carbon::parse($activity->created_at)->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-slate-400">
                    <i class="fa-regular fa-calendar-xmark text-3xl mb-2 opacity-50"></i>
                    <p class="text-xs font-medium">No recent activity recorded</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Mobile Tab Switcher Handler (Only active on screens below lg)
function switchTab(tab) {
    const sectionStats = document.getElementById('section-stats');
    const sectionChart = document.getElementById('section-chart');
    const sectionActivity = document.getElementById('section-activity');
    const btnOverview = document.getElementById('btn-overview');
    const btnActivity = document.getElementById('btn-activity');

    if (tab === 'overview') {
        sectionStats.classList.remove('hidden');
        sectionChart.classList.remove('hidden');
        sectionActivity.classList.add('hidden');
        sectionActivity.classList.remove('flex');
        
        btnOverview.className = "flex-1 py-2 rounded-lg bg-white text-[#003366] shadow-sm transition";
        btnActivity.className = "flex-1 py-2 rounded-lg text-slate-600 transition";
    } else {
        sectionStats.classList.add('hidden');
        sectionChart.classList.add('hidden');
        sectionActivity.classList.remove('hidden');
        sectionActivity.classList.add('flex');
        
        btnActivity.className = "flex-1 py-2 rounded-lg bg-white text-[#003366] shadow-sm transition";
        btnOverview.className = "flex-1 py-2 rounded-lg text-slate-600 transition";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('activityChart').getContext('2d');
    
    // Create Gradient Fill
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(0, 51, 102, 0.25)');
    gradient.addColorStop(1, 'rgba(0, 51, 102, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels ?? []),
            datasets: [{
                label: 'Bookings',
                data: @json($data ?? []),
                borderWidth: 2.5,
                borderColor: '#003366',
                backgroundColor: gradient,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#003366',
                pointHoverRadius: 6,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    suggestedMax: 10,
                    grid: { color: 'rgba(226, 232, 240, 0.6)' },
                    ticks: { 
                        stepSize: 1,
                        font: { size: 11 }, 
                        color: '#94a3b8' 
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#94a3b8' }
                }
            }
        }
    });
});
</script>
@endpush