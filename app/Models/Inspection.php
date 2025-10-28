<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'preflight_ok',
        'postflight_ok',
        'notes',
        'damage_cost',
    ];

    protected $casts = [
        'preflight_ok' => 'boolean',
        'postflight_ok' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(\App\Models\Booking::class);
    }
}
