<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestriccionAccesoRol extends Model
{
    protected $table = 'restriccion_acceso_roles';
    protected $fillable = ['id', 'role_id', 'bloqueado'];
    public $timestamps = true;
}
