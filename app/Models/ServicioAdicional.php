<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioAdicional extends Model
{
    //
    protected $table = 'servicios_adicionales';
    protected $casts = [
        'notas' => 'array',
    ];
    protected $fillable = [
        'nombre',
        'descripcion',
        'notas',
        'precio',
        'imagen',
        'horario',
        'created_at',
        'updated_at',
        
    ];
}
