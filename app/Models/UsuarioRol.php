<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsuarioRol extends Model
{
    //
    use HasFactory;

    protected $table = 'usuario_rol';

    public $timestamps = false;
}
