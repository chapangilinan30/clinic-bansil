<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientQueue extends Model
{
    use HasFactory;

    protected $table = 'patient_queue';

    protected $fillable = [
        'patient_id',
        'status',
        'clerk_id',
        'queue_number',
        'scheduled_time',
    ];

    // Relationships
    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function clerk() {
        return $this->belongsTo(User::class, 'clerk_id');
    }
}