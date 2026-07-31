<!DOCTYPE html>
<html>

<body>

<h2>
Clinica Bansil Appointment Notice
</h2>


<p>
Dear {{ $appointment->patient_name }},
</p>


<p>
We apologize, but your appointment scheduled on
<strong>
{{ $appointment->appointment_date }}
</strong>
with
<strong>
Dr. {{ $appointment->doctor_name }}
</strong>
has been cancelled.
</p>


<p>
Reason:
</p>

<p>
Emergency situation involving the doctor.
</p>


<p>
Please contact the clinic to reschedule your appointment.
</p>


<br>


<p>
Thank you for your understanding.
</p>


<p>
Clinica Bansil
</p>


</body>

</html>