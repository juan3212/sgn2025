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
        'materia_id',
        'grado_id',
        'grupo_id',
        'intensidad_horaria',
        'profesor_id'
    ];

    public function materias() {
        return $this->belongsTo(BaseMateria::class, 'materia_id');
    }

    // Relación con Grado
    public function grado() {
        return $this->belongsTo(Grado::class, 'grado_id');
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
    public function competencias()
    {
        return $this->belongsToMany(Competencia::class, 'materia_has_competencia', 'materia_id', 'competencia_id');
                    
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }
}
