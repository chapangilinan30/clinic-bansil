<?php

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\AppointmentStatusNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;

it('disables the start button for checked-in patients who are not next in queue', function () {
    $clerk = User::factory()->create(['role' => 'clerk']);
    $doctor = User::factory()->create(['role' => 'doctor']);
    $today = now('Asia/Manila')->toDateString();

    $common = [
        'doctor_id' => $doctor->id,
        'doctor_name' => $doctor->name,
        'department' => 'General',
        'appointment_date' => $today,
    ];

    Appointment::create($common + [
        'appointment_time' => '09:00',
        'queue_number' => 1,
        'patient_name' => 'Patient 1',
        'status' => 'checked-in',
    ]);

    Appointment::create($common + [
        'appointment_time' => '09:30',
        'queue_number' => 2,
        'patient_name' => 'Patient 2',
        'status' => 'checked-in',
    ]);

    $this->actingAs($clerk)
        ->get(route('clerk.dashboard', ['schedule_date' => $today]))
        ->assertOk()
        ->assertSee('Patient 1')
        ->assertSee('Patient 2')
        ->assertSee('disabled', false);
});

it('locks the next patient while an earlier appointment is unfinished', function () {
    $clerk = User::factory()->create(['role' => 'clerk']);
    $common = [
        'doctor_id' => 'doctor-1',
        'doctor_name' => 'Dr. One',
        'department' => 'General',
        'appointment_date' => '2026-09-28',
    ];

    Appointment::create($common + [
        'appointment_time' => '09:00',
        'queue_number' => 1,
        'patient_name' => 'Patient 1',
        'status' => 'pending',
    ]);
    $patientTwo = Appointment::create($common + [
        'appointment_time' => '09:30',
        'queue_number' => 2,
        'patient_name' => 'Patient 2',
        'status' => 'pending',
    ]);

    $message = "Patient 2 is currently locked. Please complete Patient 1 or update Patient 1's status to No-Show or Cancelled before proceeding.";

    $this->actingAs($clerk)
        ->patch(route('clerk.appointments.update-status', $patientTwo), ['status' => 'checked-in'])
        ->assertSessionHas('error', $message);

    expect($patientTwo->fresh()->status)->toBe('pending');

    Appointment::where('queue_number', 1)->update(['status' => 'no-show']);

    $this->actingAs($clerk)
        ->patch(route('clerk.appointments.update-status', $patientTwo), ['status' => 'checked-in'])
        ->assertSessionHasNoErrors();

    expect($patientTwo->fresh()->status)->toBe('checked-in');
});

it('sends a readable notification when an overdue appointment becomes a no-show', function () {
    Notification::fake();

    $clerk = User::factory()->create(['role' => 'clerk']);
    $patient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);

    Appointment::create([
        'user_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'doctor_name' => $doctor->name,
        'department' => 'General',
        'appointment_date' => '2026-09-28',
        'appointment_time' => '10:00',
        'queue_number' => 1,
        'patient_name' => $patient->name,
        'status' => 'pending',
    ]);

    $this->travelTo(\Carbon\Carbon::parse('2026-09-28 10:00:59', 'Asia/Manila'));
    $this->actingAs($clerk)
        ->get(route('clerk.dashboard', ['schedule_date' => '2026-09-28']))
        ->assertOk();
    expect(Appointment::first()->status)->toBe('pending');

    $this->travelTo(\Carbon\Carbon::parse('2026-09-28 10:01:00', 'Asia/Manila'));
    $this->get(route('clerk.dashboard', ['schedule_date' => '2026-09-28']))
        ->assertOk();

    Notification::assertSentTo(
        $patient,
        AppointmentStatusNotification::class,
        fn ($notification) => $notification->message === 'Your appointment has been marked as No-Show due to missed check-in time.'
    );
});

it('notifies the patient when the scheduled command marks an appointment as a no-show', function () {
    Notification::fake();

    $patient = User::factory()->create(['role' => 'patient']);
    $doctor = User::factory()->create(['role' => 'doctor']);

    $appointment = Appointment::create([
        'user_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'doctor_name' => $doctor->name,
        'department' => 'General',
        'appointment_date' => '2026-09-28',
        'appointment_time' => '10:00',
        'queue_number' => 1,
        'patient_name' => $patient->name,
        'status' => 'pending',
    ]);

    $this->travelTo(\Carbon\Carbon::parse('2026-09-28 10:00:59', 'Asia/Manila'));
    Artisan::call('appointments:mark-no-shows');
    expect($appointment->fresh()->status)->toBe('pending');

    $this->travelTo(\Carbon\Carbon::parse('2026-09-28 10:01:00', 'Asia/Manila'));
    Artisan::call('appointments:mark-no-shows');

    expect($appointment->fresh()->status)->toBe('no-show');
    Notification::assertSentTo(
        $patient,
        AppointmentStatusNotification::class,
        fn ($notification) => str_contains($notification->message, 'marked as no-show')
    );
});