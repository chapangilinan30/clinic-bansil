<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    protected $appointment;
    protected $message;

    public function __construct($appointment, $message)
    {
        $this->appointment = $appointment;
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
{
    $dept = $this->appointment->department ?? 'General';
    $doctorName = $this->appointment->doctor->name ?? 'N/A';
    
    // Fallback message text if none was provided explicitly
    $finalMessage = $this->message ?? "Your appointment with Dr. {$doctorName} ({$dept}) has been updated.";

    return [
        'department'       => $dept,
        'doctor'           => $doctorName,
        'reason_for_visit' => $this->appointment->reason_for_visit ?? 'Regular Checkup',
        'message'          => is_string($finalMessage) ? $finalMessage : json_encode($finalMessage),
    ];
}
}
