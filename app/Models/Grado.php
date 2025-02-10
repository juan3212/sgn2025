<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grado extends Model
{
    //
    use HasFactory;

    protected $table = 'grados';

    protected $fillable = ['grado'];

    // Relación muchos-a-muchos con Usuarios (a través de usuario_grado)
    public function usuarios() {
        return $this->belongsToMany(Usuario::class, 'usuario_grado');
    }

    // Relación con Materias
    public function materias() {
        return $this->hasMany(Materia::class);
    }
}
