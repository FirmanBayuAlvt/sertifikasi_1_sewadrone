<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'drone_id',
        'start_at',
        'end_at',
        'duration_hours',
        'price',
        'deposit_paid',
        'status',
        'pickup_address',
        'notes',
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function inspection()
    {
        return $this->hasOne(Inspection::class);
    }
}
