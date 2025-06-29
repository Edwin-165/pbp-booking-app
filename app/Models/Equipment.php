<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_quantity',
    ];

    public function packages()
    {
        // Relasi many-to-many dengan tabel pivot 'package_equipment'
        return $this->belongsToMany(Package::class, 'package_equipment')->withPivot('quantity');
    }
}