<?php

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;

it('allows booking when an account has a legacy strike lock', function () {
    $patient = User::factory()->create(['role' => 'patient']);
    $patient->forceFill(['is_locked_from_booking' => true])->save();
    $doctor = User::factory()->create([
        'role' => 'doctor',
        'specialization' => 'General',
    ]);
    $patientProfile = Patient::create([
        'user_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'first_name' => $patient->name,
        'last_name' => $patient->surname,
        'birth_date' => $patient->birthdate->toDateString(),
        'gender' => $patient->sex,
        'contact_number' => $patient->contact,
        'address' => $patient->address,
    ]);

    $this->actingAs($patient)
        ->get(route('patient.booking.step1'))
        ->assertOk();

    $this->withSession(['booking' => [
        'doctor_id' => $doctor->id,
        'date' => now()->addDay()->toDateString(),
        'time' => '10:00',
        'patient_name' => $patient->name,
        'patient_email' => $patient->email,
        'patient_phone' => $patient->contact,
        'notes' => '',
    ]])
        ->post(route('patient.booking.store'))
        ->assertRedirect(route('patient.dashboard'))
        ->assertSessionHas('success', 'Appointment booked successfully!');

    $appointment = Appointment::where('user_id', $patient->id)->first();
    expect($appointment)->not->toBeNull()
        ->and($appointment->patient_id)->toBe($patientProfile->id);
});

it('still blocks booking while a consultation appointment is active', function () {
    $patient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);

    Appointment::create([
        'user_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'doctor_name' => $doctor->name,
        'department' => 'General',
        'appointment_date' => now()->addDay()->toDateString(),
        'appointment_time' => '10:00',
        'queue_number' => 1,
        'patient_name' => $patient->name,
        'status' => 'pending',
    ]);

    $this->actingAs($patient)
        ->get(route('patient.booking.step1'))
        ->assertRedirect(route('patient.dashboard'))
        ->assertSessionHas('error', 'You already have an active appointment. Please wait until it is completed before booking again.');

    $this->withSession(['booking' => [
        'doctor_id' => $doctor->id,
        'date' => now()->addDays(2)->toDateString(),
        'time' => '11:00',
        'patient_name' => $patient->name,
        'patient_email' => $patient->email,
        'patient_phone' => $patient->contact,
        'notes' => '',
    ]])
        ->post(route('patient.booking.store'))
        ->assertRedirect(route('patient.dashboard'))
        ->assertSessionHas('error', 'You already have an active appointment. Please wait until it is completed before booking again.');

    expect(Appointment::where('user_id', $patient->id)->count())->toBe(1);
});
