<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class MarkAppointmentsAsNoShow extends Command
{
    protected $signature = 'appointments:mark-no-shows';
    protected $description = 'Automatically mark pending appointments as no-show when the scheduled appointment time has passed without a check-in';

    public function handle()
    {
        $now = Carbon::now('Asia/Manila');

        $this->info('Current Time: ' . $now->toDateTimeString());

        $pendingAppointments = Appointment::whereIn('status', ['pending', 'booked'])
            ->whereDate('appointment_date', $now->toDateString())
            ->get();

        $this->info('Total pending/booked appointments found for today: ' . $pendingAppointments->count());

        foreach ($pendingAppointments as $appt) {
            if (!$appt->appointment_date || !$appt->appointment_time) {
                continue;
            }

            $appointmentDateTime = Carbon::parse($appt->appointment_date->toDateString() . ' ' . $appt->appointment_time, 'Asia/Manila');

            $this->info("Checking ID {$appt->id}: Appointment time is {$appointmentDateTime->toDateTimeString()}");

            if ($now->greaterThanOrEqualTo($appointmentDateTime)) {
                $appt->update([
                    'status' => 'no-show',
                    'cancel_reason' => 'Auto-cancelled: patient did not check in before the scheduled appointment time.',
                ]);

                $this->info(' -> Marked as no-show automatically because the appointment time has already passed.');
            } else {
                $this->info(' -> Appointment is still before the scheduled time.');
            }
        }

        return self::SUCCESS;
    }
}