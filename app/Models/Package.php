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
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'daily_price' => 'decimal:2',
    ];

    public function equipment()
    {
        // Relasi many-to-many dengan tabel pivot 'package_equipment'
        return $this->belongsToMany(Equipment::class, 'package_equipment')->withPivot('quantity');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}