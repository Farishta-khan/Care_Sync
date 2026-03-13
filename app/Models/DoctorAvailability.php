<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class DoctorAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'available_date',
        'start_time',
        'end_time',
        'slot_duration',
    ];

    protected $casts = [
        'slot_duration' => 'integer',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'available_date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(\App\Models\User::class, 'doctor_id');
    }

    public function timeSlots()
    {
        return $this->hasMany(\App\Models\TimeSlot::class, 'availability_id');
    }

    public function generateSlots()
    {
        $duration = (int) $this->slot_duration;
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        while ($start->lt($end)) {
            \App\Models\TimeSlot::create([
                'doctor_id' => $this->doctor_id,
                'availability_id' => $this->id,
                'slot_date' => $this->available_date,
                'slot_time' => $start->format('H:i:s'),
                'is_booked' => false,
            ]);

            $start->addMinutes($duration);
        }
    }
}