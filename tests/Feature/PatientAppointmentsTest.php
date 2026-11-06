<?php

use App\Models\Appointment;
use App\Models\User;

it('shows every active appointment status on the patient appointments page', function () {
    $patient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);
    $statuses = [
        'pending',
        'booked',
        'confirmed',
        'checked-in',
        'called',
        'in_progress',
        'in-progress',
        'in-session',
    ];

    foreach ($statuses as $index => $status) {
        Appointment::create([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'doctor_name' => $doctor->name,
            'department' => 'General',
            'appointment_date' => now()->addDays($index + 1)->toDateString(),
            'appointment_time' => '10:00',
            'queue_number' => $index + 1,
            'patient_name' => "Patient for {$status}",
            'status' => $status,
        ]);
    }

    $response = $this->actingAs($patient)->get(route('patient.appointments'));

    $response->assertOk();
    foreach ($statuses as $status) {
        $response->assertSee(ucfirst(str_replace('_', '-', $status)));
    }
});

it('renders the patient dashboard, appointments, and history pages', function () {
    $patient = User::factory()->create(['role' => 'patient']);

    $this->actingAs($patient)
        ->get(route('patient.dashboard'))
        ->assertOk();

    $this->get(route('patient.appointments'))
        ->assertOk();

    $this->get(route('patient.history'))
        ->assertOk();
});

it('shows booking notes in the visit history when no separate reason is stored', function () {
    $patient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);

    Appointment::create([
        'user_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'doctor_name' => $doctor->name,
        'department' => 'General',
        'appointment_date' => now()->subDay()->toDateString(),
        'appointment_time' => '10:00',
        'queue_number' => 1,
        'patient_name' => $patient->name,
        'purpose' => 'Consultation',
        'notes' => 'Visit for seasonal allergies',
        'status' => 'completed',
    ]);

    $this->actingAs($patient)
        ->get(route('patient.history'))
        ->assertOk()
        ->assertSee('Visit for seasonal allergies');
});
