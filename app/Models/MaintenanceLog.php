<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'drone_id',
        'date',
        'issue',
        'action',
        'cost',
        'technician_id',
    ];

    protected $dates = ['date'];

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }

    public function technician()
    {
        return $this->belongsTo(\App\Models\User::class, 'technician_id');
    }
}
