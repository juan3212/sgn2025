<?php

namespace App\Models;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    //
    use HasFactory, Notifiable;

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
