<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}