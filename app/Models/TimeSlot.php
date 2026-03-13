<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'doctor_id',
        'availability_id',
        'slot_date',
        'slot_time',
        'is_booked',
    ];

    protected $casts = [
        'is_booked' => 'boolean',
        'slot_date' => 'date',
        'slot_time' => 'string', // match DB `time`
    ];

    public function availability()
    {
        return $this->belongsTo(\App\Models\DoctorAvailability::class, 'availability_id');
    }

    public function doctor()
    {
        return $this->belongsTo(\App\Models\User::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->hasOne(\App\Models\Appointment::class, 'slot_id');
    }
}