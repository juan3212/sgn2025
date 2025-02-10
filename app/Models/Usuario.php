<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    //
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre', 
        'apellido', 
        'nuip', 
        'correo', 
        'password_hash'
    ];

    // Relación muchos-a-muchos con Roles
    public function roles() {
        return $this->belongsToMany(Rol::class, 'usuario_rol');
    }

    // Relación muchos-a-muchos con Grados y Grupos (a través de usuario_grado)
    public function gradosGrupos() {
        return $this->belongsToMany(Grado::class, 'usuario_grado')
            ->withPivot('grupo_id');
    }

    // Relación con Notas (como estudiante)
    public function notas() {
        return $this->hasMany(Nota::class, 'estudiante_id');
    }

    // Relación con Materias (como profesor)
    public function materiasComoProfesor() {
        return $this->hasMany(Materia::class, 'profesor_id');
    }
}
