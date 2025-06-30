<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'daily_price',
        'stock',
    ];

    protected $casts = [
        'daily_price' => 'decimal:2',
        'stock' => 'integer'
    ];

    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}