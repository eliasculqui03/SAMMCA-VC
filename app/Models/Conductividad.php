<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductividad extends Model
{
    use HasFactory;

    protected $fillable = [
        'valor',
        'fecha_hora',
    ];
}
