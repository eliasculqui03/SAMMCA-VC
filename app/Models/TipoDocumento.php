<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion_larga',
        'descripcion_corta',
        'estado',
    ];

    public function miembros(): HasMany
    {
        return $this->hasMany(Miembro::class);
    }
}
