<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorUnavailability extends Model
{
    // Allows Laravel to save data into these columns
    protected $fillable = [
        'doctor_id',
        'date',
    ];

    // Link back to the Doctor (User)
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}