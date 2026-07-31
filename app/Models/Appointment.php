<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Prescription;
use App\Models\Service;

class Appointment extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'user_id',       // patient user id
        'patient_id',    // optional (maybe not used)
        'doctor_id',
        'service_id',
        'queue_number',
        'patient_name',
        'patient_email',
        'patient_phone',
        'doctor_name',
        'department',
        'status',
        'is_walk_in',
        'reason',
        'notes',
        'appointment_date',
        'appointment_time',
        'diagnosis',
        'medicines',
        'purpose',
        'cancel_reason', // ✅ Added to allow saving cancellation details securely
    ];

    // Cast fields to proper types
    protected $casts = [
        'is_walk_in' => 'boolean',
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'medicines' => 'array',
    ];

    // ================= RELATIONSHIPS =================

    // The patient (user) who booked this appointment
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias for the patient relationship (allows $appointment->user to work)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // The doctor assigned to this appointment
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Prescription for this appointment
    public function prescription()
    {
        return $this->hasOne(Prescription::class, 'appointment_id');
    }

    // ================= HELPER METHODS =================

    // Check if appointment is a walk-in
    public function isWalkIn(): bool
    {
        return (bool) $this->is_walk_in;
    }

    // Info about the patient
    public function userInfo(): string
    {
        if ($this->patient) {
            return $this->patient->name . ' (' . $this->patient->email . ')';
        }

        return $this->patient_name ?? 'Unknown';
    }

    // Info about the doctor
    public function doctorInfo(): string
    {
        if ($this->doctor) {
            return $this->doctor->name;
        }

        return $this->doctor_name ?? 'Unknown';
    }
}