<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Notifications\AppointmentStatusNotification;
use Carbon\Carbon;

class MarkAppointmentsAsNoShow extends Command
{
    protected $signature = 'appointments:mark-no-shows';
    protected $description = 'Mark pending appointments as no-show one minute after their scheduled time';

    public function handle()
{
    $now = Carbon::now('Asia/Manila');
    $this->info("Current Time: " . $now->toTimeString());

    // Let's find ALL pending appointments for today, regardless of time
    $allPending = Appointment::where('status', 'pending')
        ->whereDate('appointment_date', $now->toDateString())
        ->get();

    $this->info("Total pending appointments found for today: " . $allPending->count());

    foreach ($allPending as $appt) {
        $this->info("Checking ID {$appt->id}: Appointment Time is {$appt->appointment_time}");

        $appointmentDate = Carbon::parse($appt->appointment_date)->toDateString();
        $appointmentTime = Carbon::parse($appt->appointment_time)->format('H:i:s');
        $appointmentDateTime = Carbon::parse("{$appointmentDate} {$appointmentTime}", 'Asia/Manila');

        if ($appointmentDateTime->copy()->addMinute()->lessThanOrEqualTo($now)) {
            $appt->update(['status' => 'no-show']);
            if ($appt->user) {
                $appt->user->notify(new AppointmentStatusNotification(
                    "Your appointment at {$appt->appointment_time} was marked as no-show because you did not check in by the scheduled time."
                ));
            }
            $this->info(" -> Marked as no-show!");
        } else {
            $this->info(" -> Too early to mark as no-show.");
        }
    }
}
}