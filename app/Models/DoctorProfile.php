<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'experience',
        'bio',
        'profile_image',
        'hourly_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
