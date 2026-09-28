<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class MarkAppointmentsAsNoShow extends Command
{
    protected $signature = 'appointments:mark-no-shows';
    protected $description = 'Automatically mark pending appointments as no-show if not checked in 20 minutes before time';

    public function handle()
{
    $now = Carbon::now('Asia/Manila');
    $threshold = $now->copy()->subMinutes(20);

    $this->info("Current Time: " . $now->toTimeString());
    $this->info("Threshold (Time - 20m): " . $threshold->toTimeString());

    // Let's find ALL pending appointments for today, regardless of time
    $allPending = Appointment::where('status', 'pending')
        ->whereDate('appointment_date', $now->toDateString())
        ->get();

    $this->info("Total pending appointments found for today: " . $allPending->count());

    foreach ($allPending as $appt) {
        $this->info("Checking ID {$appt->id}: Appointment Time is {$appt->appointment_time}");
        
        if ($appt->appointment_time <= $threshold->toTimeString()) {
            $appt->update(['status' => 'no-show']);
            $this->info(" -> Marked as no-show!");
        } else {
            $this->info(" -> Too early to mark as no-show.");
        }
    }
}
}