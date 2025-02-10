<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materia extends Model
{
    //
    use HasFactory;

    protected $table = 'materias';

    protected $fillable = [
        'nombre',
        'grado_id',
        'grupo_id',
        'intensidad_horaria',
        'profesor_id'
    ];

    // Relación con Grado
    public function grado() {
        return $this->belongsTo(Grado::class);
    }

    // Relación con Grupo
    public function grupo() {
        return $this->belongsTo(Grupo::class);
    }

    // Relación con Profesor (Usuario)
    public function profesor() {
        return $this->belongsTo(Usuario::class, 'profesor_id');
    }

    // Relación con Competencias
    public function competencias() {
        return $this->hasMany(Competencia::class);
    }

    // Relación con Notas
    public function notas() {
        return $this->hasMany(Nota::class);
    }
}
