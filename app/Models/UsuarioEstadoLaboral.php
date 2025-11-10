<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioEstadoLaboral extends Model
{
    //
    protected $table = 'usuario_estado_laboral';
    protected $fillable = [
        'usuario_id',
        'estado_laboral',
        'empresa'
    ];
}
