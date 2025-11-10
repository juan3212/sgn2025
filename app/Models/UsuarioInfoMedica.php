<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioInfoMedica extends Model
{
    //
    protected $table = 'usuario_info_medica';
    protected $fillable = [
        'usuario_id',
        'rh',
        'eps',
        'alergias',
        'medicamentos',
        'enfermedades'
    ];
}
