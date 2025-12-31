<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'car_id',
        'start_date',
        'end_date',
        'total_days',
        'total_cost',
        'status'
    ];

    // Cast tanggal ke Carbon instance
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi belongsTo dengan customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi belongsTo dengan car
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // Accessor untuk format tanggal
    public function getFormattedStartDateAttribute()
    {
        return Carbon::parse($this->start_date)->format('d/m/Y');
    }

    public function getFormattedEndDateAttribute()
    {
        return Carbon::parse($this->end_date)->format('d/m/Y');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => 'badge bg-success',
            'completed' => 'badge bg-info',
            'cancelled' => 'badge bg-danger'
        ];
        
        return '<span class="' . ($badges[$this->status] ?? 'badge bg-secondary') . '">' 
               . ucfirst($this->status) . '</span>';
    }
}