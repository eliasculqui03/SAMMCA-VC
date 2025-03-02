<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_cargo',
        'decripcion',
        'estado',
    ];

    public function miembros(): HasMany
    {
        return $this->hasMany(Miembro::class);
    }
}
