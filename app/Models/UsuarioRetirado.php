<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioRetirado extends Model
{
    protected $table = "usuario_retirado";
    protected $fillable = ["usuario_id", "motivo_retiro"];
}
