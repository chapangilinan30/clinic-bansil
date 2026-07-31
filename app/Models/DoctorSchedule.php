<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorSchedule extends Model
{
    use HasFactory;

    /* ===============================
        TABLE FILLABLE FIELDS
    =============================== */
    protected $fillable = [
        'doctor_id',
        'day',
        'date',
        'start_time',
        'end_time',
        'slot_duration',
    ];

    /* ===============================
        RELATIONSHIP: DOCTOR
    =============================== */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /* ===============================
        ACCESSOR: FULL TIME RANGE
    =============================== */
    public function getScheduleTimeAttribute()
    {
        return "{$this->start_time} - {$this->end_time}";
    }

    /* ===============================
        SCOPE: FILTER BY DOCTOR
    =============================== */
    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /* ===============================
        SCOPE: WEEKLY SCHEDULES ONLY
        (no specific date)
    =============================== */
    public function scopeWeekly($query)
    {
        return $query->whereNull('date');
    }

    /* ===============================
        SCOPE: DATE-BASED SCHEDULES ONLY
    =============================== */
    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /* ===============================
        HELPER: CHECK IF SCHEDULE IS WEEKLY
    =============================== */
    public function isWeekly()
    {
        return is_null($this->date);
    }

    /* ===============================
        HELPER: CHECK IF SCHEDULE IS DATE-BASED
    =============================== */
    public function isDateBased()
    {
        return !is_null($this->date);
    }
}