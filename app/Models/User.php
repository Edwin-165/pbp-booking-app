<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'alamat',
        'no_telp',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    
    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
