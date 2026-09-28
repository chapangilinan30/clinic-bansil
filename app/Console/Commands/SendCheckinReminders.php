<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;
use App\Notifications\AppointmentStatusNotification;

class SendCheckinReminders extends Command
{
    protected $signature = 'appointments:checkin-reminder {--test : Run in test mode for immediate notifications}';
    protected $description = 'Send reminders 20 minutes before appointments and mark no-shows one minute after their scheduled time';

    public function handle()
    {
        $now = Carbon::now('Asia/Manila');
        $today = $now->toDateString();

        // 🔔 Determine reminder and no-show times
        if ($this->option('test')) {
            // Test mode: send notifications for any pending appointment today
            $appointmentsForReminder = Appointment::where('status', 'pending')
                ->where('appointment_date', $today)
                ->get();

            $appointmentsNoShow = Appointment::where('status', 'pending')
                ->where('appointment_date', $today)
                ->get();
        } else {
            // Normal mode
            $reminderTime = $now->copy()->addMinutes(20)->format('H:i');
            $noShowCutoff = $now->copy()->subMinute()->format('H:i:s');
            $appointmentsForReminder = Appointment::where('status', 'pending')
                ->where('appointment_date', $today)
                ->where('appointment_time', $reminderTime)
                ->get();

            $appointmentsNoShow = Appointment::where('status', 'pending')
                ->where('appointment_date', $today)
                ->whereTime('appointment_time', '<=', $noShowCutoff)
                ->get();
        }

        // ================== SEND REMINDERS ==================
        foreach ($appointmentsForReminder as $appointment) {
            $patient = $appointment->patient;
            if ($patient) {
                $patient->notify(new AppointmentStatusNotification(
                    "Reminder: Please check-in for your appointment at {$appointment->appointment_time}."
                ));
                $this->info("✅ Reminder sent to patient ID {$patient->id} for appointment ID {$appointment->id}");
            }
        }

        // ================== MARK NO-SHOWS ==================
        foreach ($appointmentsNoShow as $appointment) {
            if (!$this->option('test')) {
                $appointment->update(['status' => 'no-show']);
            }

            $patient = $appointment->patient;
            if ($patient) {
                $patient->notify(new AppointmentStatusNotification(
                    $this->option('test')
                        ? "Test notification: Appointment ID {$appointment->id} is pending."
                        : "You missed your appointment at {$appointment->appointment_time}. It has been marked as no-show."
                ));
                $this->info("ℹ️ Notification sent to patient ID {$patient->id} for appointment ID {$appointment->id}");
            }
        }

        $this->info('✅ Check-in reminders and no-show notifications processed.');
    }
}