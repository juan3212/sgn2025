<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioInformacion extends Model
{
    //
    protected $table = 'usuario_informacion';
    protected $fillable = [
        'usuario_id',
        'fecha_nacimiento',
        'departamento_nacimiento',
        'municipio_nacimiento',
        'sexo',
        'religion',
    ];
}
