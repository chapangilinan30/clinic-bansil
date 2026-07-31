<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    /* ===============================
        MASS ASSIGNABLE FIELDS
    =============================== */
    protected $fillable = [
        'first_name',
        'last_name',
        'mi',             // ADDED: To match your M.I. form input
        'birth_date',
        'gender',
        'contact_number',
        'address',
        'medical_history',
        'user_id',
        'doctor_id',        // ADDED: Very important for linking this to a login account
    ];

    /* ===============================
        RELATIONSHIPS
    =============================== */

    /**
     * Link to the login account (User model)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A patient belongs to a doctor (User model)
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function notes()
    {
        return $this->hasMany(PatientNote::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /* ===============================
        ACCESSORS
    =============================== */

    /**
     * Get full name (includes MI if it exists)
     */
    public function getFullNameAttribute()
    {
        return $this->mi 
            ? "{$this->first_name} {$this->mi}. {$this->last_name}" 
            : "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope to get patients by doctor
     */
    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}