<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'diagnosis',
        'next_appointment_date', // 🔥 ADD THIS LINE
        'appointment_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * A prescription belongs to a patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * A prescription belongs to a doctor (User)
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * A prescription has many prescription items
     */
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * Optional: Access medicines directly through items
     * (Advanced / Cleaner for future use)
     */
    public function medicines()
    {
        return $this->hasManyThrough(
            Medicine::class,
            PrescriptionItem::class,
            'prescription_id', // Foreign key on PrescriptionItem
            'id',              // Foreign key on Medicine
            'id',              // Local key on Prescription
            'medicine_id'      // Local key on PrescriptionItem
        );
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}