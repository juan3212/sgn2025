<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioHasServicio extends Model
{
    //
    protected $table = 'usuario_has_servicio';
    protected $fillable = [
        'usuario_id',
        'servicio_id',
    ];
}
