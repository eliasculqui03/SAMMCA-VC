<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Miembro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_documento_id',
        'numero_documento',
        'edad',
        'telefono',
        'correo',
        'direccion',
        'cargo_id',
        'inicio_periodo',
        'final_periodo',
        'estado',
    ];

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
}
