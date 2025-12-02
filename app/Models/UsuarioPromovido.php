<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioPromovido extends Model
{
    //
    protected $table = "usuarios_promovidos";
    protected $fillable = [
        "usuario_id",
        "grado_actual",
        "grado_destino",
    ];
}
