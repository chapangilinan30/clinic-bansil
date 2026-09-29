<?php

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\AppointmentStatusNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;

it('orders the clerk queue by appointment time instead of booking order', function () {
    $clerk = User::factory()->create(['role' => 'clerk']);
    $doctor = User::factory()->create(['role' => 'doctor']);
    $today = now('Asia/Manila')->toDateString();

    $common = [
        'doctor_id' => $doctor->id,
        'doctor_name' => $doctor->name,
        'department' => 'General',
        'appointment_date' => $today,
    ];

    $bookedFirst = Appointment::create($common + [
        'appointment_time' => '07:00',
        'queue_number' => 1,
        'patient_name' => 'Patient 1',
        'status' => 'checked-in',
    ]);

    $bookedSecond = Appointment::create($common + [
        'appointment_time' => '06:00',
        'queue_number' => 2,
        'patient_name' => 'Patient 2',
        'status' => 'checked-in',
    ]);

    $this->actingAs($clerk)
        ->get(route('clerk.dashboard', ['schedule_date' => $today]))
        ->assertOk()
        ->assertSee('Patient 1')
        ->assertSee('Patient 2')
        ->assertViewHas('queue', function ($queue) use ($bookedFirst, $bookedSecond) {
            return $queue->pluck('id')->all() === [$bookedSecond->id, $bookedFirst->id];
        });
});

it('allows the earlier appointment time to start even when booked second', function () {
    $clerk = User::factory()->create(['role' => 'clerk']);
    $doctor = User::factory()->create(['role' => 'doctor']);
    $common = [
        'doctor_id' => $doctor->id,
        'doctor_name' => $doctor->name,
        'department' => 'General',
        'appointment_date' => '2026-09-28',
    ];

    $bookedFirst = Appointment::create($common + [
        'appointment_time' => '07:00',
        'queue_number' => 1,
        'patient_name' => 'Patient 1',
        'status' => 'pending',
    ]);
    $bookedSecond = Appointment::create($common + [
        'appointment_time' => '06:00',
        'queue_number' => 2,
        'patient_name' => 'Patient 2',
        'status' => 'checked-in',
    ]);

    $message = 'This appointment is waiting for an earlier appointment time to be completed, cancelled, or marked as no-show.';

    $this->actingAs($clerk)
        ->patch(route('clerk.appointments.update-status', $bookedSecond), ['status' => 'called'])
        ->assertSessionHasNoErrors();

    expect($bookedSecond->fresh()->status)->toBe('called');

    $this->patch(route('clerk.appointments.update-status', $bookedFirst), ['status' => 'called'])
        ->assertSessionHas('error', $message);

    expect($bookedFirst->fresh()->status)->toBe('pending');

    $bookedSecond->update(['status' => 'no-show']);

    $this->actingAs($clerk)
        ->patch(route('clerk.appointments.update-status', $bookedFirst), ['status' => 'called'])
        ->assertSessionHasNoErrors();

    expect($bookedFirst->fresh()->status)->toBe('called');
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