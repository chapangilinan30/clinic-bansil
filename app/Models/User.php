<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'surname',
        'mi',
        'email',
        'email_verified_at', // <-- IDINAGDAG: Para pumayag sa Mass Assignment kapag nag-create sa Admin
        'password',
        'birthdate',
        'sex',
        'contact',
        'address',
        'role',
        'status',
        'specialization',
        'is_admin',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
        'is_admin' => 'boolean',
    ];

    // ================= EMAIL VERIFICATION BYPASS =================

    /**
     * Determine if the user has verified their email address.
     * Override default MustVerifyEmail behavior for Doctors, Admins, Clerks, and Staff.
     */
    public function hasVerifiedEmail(): bool
    {
        // Kung admin, doctor, clerk, o staff, Matic Verified na sila agad!
        if (in_array($this->role, ['admin', 'doctor', 'clerk', 'staff'])) {
            return true;
        }

        // Para sa regular users/patients, iche-check pa rin ang email_verified_at timestamp
        return ! is_null($this->email_verified_at);
    }

    // ================= RELATIONSHIPS =================

    /**
     * Patient appointments (user = patient)
     */
    public function appointmentsAsPatient(): HasMany
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    /**
     * Doctor appointments
     */
    public function appointmentsAsDoctor(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Alias for doctor appointments
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Doctor unavailabilities
     */
    public function unavailabilities(): HasMany
    {
        return $this->hasMany(DoctorUnavailability::class, 'doctor_id');
    }

    // ================= NOTIFICATIONS HELPER =================

    /**
     * Get unread notifications
     */
    public function unreadNotificationsList()
    {
        return $this->unreadNotifications;
    }

    /**
     * Get all notifications
     */
    public function allNotifications()
    {
        return $this->notifications;
    }
}