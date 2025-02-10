<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    //
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['rol'];

    // Relación muchos-a-muchos con Usuarios
    public function usuarios() {
        return $this->belongsToMany(Usuario::class, 'usuario_rol');
    }
}
