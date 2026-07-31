@extends('layouts.admin')

@section('title', 'System Reports')

@section('content')

<!-- Header & Filter Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#003366]">System Reports</h1>
        <p class="text-slate-500 text-xs sm:text-sm mt-1">Analytics and clinic system activity overview.</p>
    </div>

    <!-- Time Range Filter -->
    <form method="GET" action="{{ route('admin.reports.index') }}" class="relative inline-block">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-regular fa-calendar-days text-sm"></i>
            </div>
            <select name="range" onchange="this.form.submit()" 
                    class="w-full sm:w-auto border border-slate-200 bg-white rounded-xl pl-10 pr-10 py-2.5 text-xs font-semibold text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#003366]/20 focus:border-[#003366] appearance-none cursor-pointer transition">
                <option value="7days" {{ ($range ?? '') == '7days' ? 'selected' : '' }}>Last 7 Days Activity</option>
                <option value="month" {{ ($range ?? '') == 'month' ? 'selected' : '' }}>Last Month Activity</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-chevron-down text-[10px]"></i>
            </div>
        </div>
    </form>
</div>

<!-- MOBILE VIEW TAB SWITCHER (Visible ONLY on mobile/tablet screens < lg) -->
<div class="flex lg:hidden bg-slate-200/60 p-1 rounded-xl mb-6 text-xs font-semibold">
    <button id="btn-accounts" onclick="switchReportTab('accounts')" class="flex-1 py-2 rounded-lg bg-white text-[#003366] shadow-sm transition">
        Accounts & Activity
    </button>
    <button id="btn-switches" onclick="switchReportTab('switches')" class="flex-1 py-2 rounded-lg text-slate-600 transition">
        Admin Mode Switches
    </button>
</div>

<!-- SECTION 1: REPORT SUMMARY CARDS (Part of Tab 1 on Mobile, Always Visible on Desktop) -->
<div id="section-report-summary" class="block mb-6 lg:mb-8">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">
        
        <!-- Accounts Created -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Accounts Created</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-bold text-[#003366] tracking-tight">{{ number_format($accountsCreated ?? 0) }}</span>
                    <span class="text-[10px] sm:text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">New</span>
                </div>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user-plus text-sm sm:text-lg"></i>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Total Active Users</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-bold text-emerald-600 tracking-tight">{{ number_format($activeAccounts ?? 0) }}</span>
                    <span class="text-[10px] sm:text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
                </div>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-users text-sm sm:text-lg"></i>
            </div>
        </div>

        <!-- Service Updates -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] sm:text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Service Updates</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-bold text-amber-600 tracking-tight">{{ number_format($serviceUpdates ?? 0) }}</span>
                    <span class="text-[10px] sm:text-[11px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Logs</span>
                </div>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-arrows-rotate text-sm sm:text-lg"></i>
            </div>
        </div>

    </div>
</div>

<!-- SYSTEM ACTIVITY MAIN GRID (Restored original desktop grid layout) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- System Activity Bar Chart (Spans 2 columns on desktop) -->
    <div id="section-report-chart" class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 w-full flex flex-col justify-between block">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <div>
                <h2 class="font-bold text-slate-800 text-base sm:text-lg">System Activity Report</h2>
                <p class="text-xs text-slate-400 mt-0.5">Comparative log distribution for selected timeframe</p>
            </div>
            <span class="text-[10px] sm:text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">
                <i class="fa-solid fa-chart-column text-slate-400 mr-1.5"></i> Bar Chart
            </span>
        </div>

        <div class="h-64 sm:h-80 w-full">
            <canvas id="reportBarChart"></canvas>
        </div>
    </div>

    <!-- Admin Mode Switches Sidebar Widget (1 column on desktop) -->
    <div id="section-report-switches" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 flex-col justify-between hidden lg:flex">
        <div>
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-slate-800 text-base sm:text-lg">Admin Mode Switches</h2>
                <i class="fa-solid fa-user-shield text-[#003366] text-base"></i>
            </div>

            <div class="space-y-3 overflow-y-auto max-h-[320px] pr-1">
                @forelse($adminSwitches ?? $activities ?? [] as $log)
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100/60 transition duration-150">
                        <div class="w-8 h-8 rounded-full bg-[#003366]/10 text-[#003366] font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                            {{ strtoupper(substr($log->user->name ?? $log->user_name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-semibold text-slate-800 truncate">
                                    {{ $log->user->name ?? $log->user_name ?? 'Unknown User' }}
                                </p>
                                <span class="text-[10px] font-semibold text-[#003366] bg-blue-50 px-2 py-0.5 rounded-full shrink-0">
                                    {{ ucfirst($log->user->role ?? $log->role ?? 'Clerk') }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1">
                                <i class="fa-solid fa-shield-halved text-[9px] text-[#003366]"></i>
                                Switched to Admin Mode
                            </p>
                            <span class="text-[10px] text-slate-400 block mt-1">
                                <i class="fa-regular fa-clock text-[9px] mr-1"></i>
                                {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y • h:i A') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <i class="fa-solid fa-user-shield text-3xl mb-2 opacity-40"></i>
                        <p class="text-xs font-medium">No admin mode switches recorded</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Mobile Tab Switcher Handler (Only active on screens below lg)
function switchReportTab(tab) {
    const summary = document.getElementById('section-report-summary');
    const chart = document.getElementById('section-report-chart');
    const switches = document.getElementById('section-report-switches');
    const btnAccounts = document.getElementById('btn-accounts');
    const btnSwitches = document.getElementById('btn-switches');

    if (tab === 'accounts') {
        summary.classList.remove('hidden');
        chart.classList.remove('hidden');
        switches.classList.add('hidden');
        switches.classList.remove('flex');

        btnAccounts.className = "flex-1 py-2 rounded-lg bg-white text-[#003366] shadow-sm transition";
        btnSwitches.className = "flex-1 py-2 rounded-lg text-slate-600 transition";
    } else {
        summary.classList.add('hidden');
        chart.classList.add('hidden');
        switches.classList.remove('hidden');
        switches.classList.add('flex');

        btnSwitches.className = "flex-1 py-2 rounded-lg bg-white text-[#003366] shadow-sm transition";
        btnAccounts.className = "flex-1 py-2 rounded-lg text-slate-600 transition";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('reportBarChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{
                label: 'System Activity',
                data: @json($chartValues ?? []),
                backgroundColor: 'rgba(0, 51, 102, 0.85)',
                hoverBackgroundColor: '#003366',
                borderRadius: 8,
                borderSkipped: false,
                barThickness: 28
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
                    grid: { color: 'rgba(226, 232, 240, 0.6)' },
                    ticks: { font: { size: 11 }, color: '#94a3b8' }
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