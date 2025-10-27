<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'drone_id',
        'start_date',
        'end_date',
        'status',
        'fine',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }
}