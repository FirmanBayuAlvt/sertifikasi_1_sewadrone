<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'serial_no',
        'specs',
        'description',
        'hourly_rate',
        'daily_rate',
        'deposit',
        'status',
        'image',
    ];

    protected $casts = [
        'specs' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function units()
    {
        return $this->hasMany(DroneUnit::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_drone');
    }
}
