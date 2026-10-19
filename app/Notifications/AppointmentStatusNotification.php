<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    protected $appointment;
    protected $message;

    public function __construct($appointment, $message = null)
    {
        $this->appointment = $appointment;
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        // Supports both database logs and email notifications
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        $dept = $this->appointment->department ?? 'General';
        $doctorName = $this->appointment->doctor->name ?? 'N/A';
        
        $finalMessage = $this->message ?? "Your appointment with Dr. {$doctorName} ({$dept}) has been updated.";

        return [
            'department'       => $dept,
            'doctor'           => $doctorName,
            'reason_for_visit' => $this->appointment->reason_for_visit ?? 'Regular Checkup',
            'message'          => is_string($finalMessage) ? $finalMessage : json_encode($finalMessage),
            'appointment_id'   => $this->appointment->id ?? null,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        $dept = $this->appointment->department ?? 'General';
        $doctorName = $this->appointment->doctor->name ?? 'N/A';
        $finalMessage = $this->message ?? "Your appointment with Dr. {$doctorName} ({$dept}) has been updated.";

        return (new MailMessage)
            ->subject('Clinic Appointment Update')
            ->line($finalMessage)
            ->line("Department: {$dept}")
            ->line("Doctor: Dr. {$doctorName}");
    }
}