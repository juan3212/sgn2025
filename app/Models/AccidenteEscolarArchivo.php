<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccidenteEscolarArchivo extends Model
{
    protected $table = 'accidente_escolar_archivo';

    protected $fillable = [
        'registro_accidente_id',
        'usuario_id',
        'nombre_archivo',
        'path',
    ];

    public function registroAccidenteUsuario()
    {
        return $this->belongsTo(RegistroAccidenteUsuario::class, 'registro_accidente_usuario_id');
    }
}
