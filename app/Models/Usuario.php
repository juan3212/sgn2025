<?php

namespace App\Models;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class Usuario extends Authenticatable
{
    //
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre', 
        'apellido', 
        'nuip', 
        'correo', 
        'password_hash'
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relación muchos-a-muchos con Roles
    /*public function roles() {
        return $this->belongsToMany(Rol::class, 'usuario_rol');
    }*/

    // Relación muchos-a-muchos con Grados y Grupos (a través de usuario_grado)
    public function grados() {
        return $this->belongsToMany(Grado::class, 'usuario_grado');
    }

    public function grupos() {
        return $this->belongsToMany(Grupo::class, 'usuario_grado');
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
