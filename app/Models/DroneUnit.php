<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DroneUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'drone_id',
        'code',
        'name',
        'status',
        'notes',
    ];

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }

    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }
    public function unit()
    {
        return $this->belongsTo(\App\Models\DroneUnit::class, 'drone_unit_id');
    }
}
