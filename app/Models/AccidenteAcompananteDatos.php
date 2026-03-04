<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccidenteAcompananteDatos extends Model
{
    protected $table = 'accidente_acompanante_datos';

    protected $fillable = [
        'registro_accidente_id',
        'nombre',
        'numero_identificacion',
        'vinculo_con_estudiante',
        'telefono',
        'hora',
    ];

    public function registroAccidenteUsuario()
    {
        return $this->belongsTo(RegistroAccidenteUsuario::class, 'registro_accidente_usuario_id');
    }
}
