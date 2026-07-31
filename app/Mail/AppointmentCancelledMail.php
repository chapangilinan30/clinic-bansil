<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelledMail extends Mailable
{
    use Queueable, SerializesModels;


    public $appointment;


    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }


    public function build()
    {
        return $this->subject(
                'Appointment Cancellation Notice'
            )
            ->view('emails.appointment-cancelled');
    }
}