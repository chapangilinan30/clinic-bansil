<?php

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

it('shows Manila appointments when the UTC date is still the previous day', function () {
    Carbon::setTestNow(Carbon::parse('2026-11-05 18:04:00', 'UTC'));

    $doctor = User::factory()->create(['role' => 'doctor']);
    $patient = User::factory()->create(['role' => 'patient']);

    Appointment::create([
        'user_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'doctor_name' => $doctor->name,
        'department' => 'General',
        'appointment_date' => '2026-11-06',
        'appointment_time' => '10:00',
        'queue_number' => 1,
        'patient_name' => 'Manila Test Patient',
        'patient_phone' => $patient->contact,
        'purpose' => 'Consultation',
        'status' => 'pending',
        'is_walk_in' => false,
    ]);

    $this->actingAs($doctor)
        ->get(route('doctor.dashboard'))
        ->assertOk()
        ->assertSee('Manila Test Patient');
});

afterEach(function () {
    Carbon::setTestNow();
});
