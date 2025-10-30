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
        'drone_unit_id',
        'duration_days',
        'late_days',
        'fine_per_day',
        'late_fee',
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'specs' => 'array',
        'duration_hours' => 'integer',
        'duration_days' => 'integer',
        'late_days' => 'integer',
        'fine_per_day' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'price' => 'decimal:2',
        'deposit_paid' => 'decimal:2',
    ];
    public static function getBookingConfig()
    {
        return [
            'max_days_without_fine' => config('booking.max_days_without_fine', 5),
            'fine_percent_of_daily_rate' => config('booking.fine_percent_of_daily_rate', 0.20),
        ];
    }



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
    public function unit()
    {
        return $this->belongsTo(\App\Models\DroneUnit::class, 'drone_unit_id');
    }
    public function droneUnit()
    {
        return $this->belongsTo(DroneUnit::class, 'drone_unit_id');
    }
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
