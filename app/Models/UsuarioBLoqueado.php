<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioBLoqueado extends Model
{
    //
    protected $table = "usuario_bloqueado";
    protected $fillable = [
        "usuario_id",
        "servicio",
    ];
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
