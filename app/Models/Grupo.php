<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grupo extends Model
{
    //
    use HasFactory;

    protected $table = 'grupos';

    protected $fillable = ['grupo'];

    // Relación con Materias
    public function materias() {
        return $this->hasMany(Materia::class);
    }

    // Relación con UsuarioGrado (tabla pivote)
    public function usuariosGrados() {
        return $this->hasMany(UsuarioGrado::class);
    }
}
