<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'license_plate',
        'year',
        'daily_rate',
        'status',
        'color'
    ];

    // Relasi one-to-many dengan rentals
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    // Scope untuk mobil tersedia
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}