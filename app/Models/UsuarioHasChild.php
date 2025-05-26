<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class UsuarioHasChild extends Model
{
    //
    use HasFactory;
    protected $table = "usuario_has_child";
    protected $fillable = ['usuario_id', 'child_id'];
}
