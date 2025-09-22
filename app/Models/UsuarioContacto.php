<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioContacto extends Model
{
    //
    protected $table = 'usuario_contacto';
    protected $fillable = [
        'usuario_id',
        'telefono',
        'email',
        'direccion',
    ];
}
