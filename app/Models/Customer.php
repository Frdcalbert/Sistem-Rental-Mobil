<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'driver_license'
    ];

    // Relasi one-to-many dengan rentals
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}