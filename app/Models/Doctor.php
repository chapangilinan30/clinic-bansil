<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;

    // These allow the Seeder and Controller to save names and specializations
    protected $fillable = [
        'name',
        'specialization',
    ];

    /**
     * Relationship: A doctor has many appointments.
     * This is what fixes the "Call to undefined method" error.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}