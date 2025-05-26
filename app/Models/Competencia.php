<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Competencia extends Model
{
    //
    use HasFactory;

    protected $table = 'competencias';

    protected $fillable = ['nombre', 'descripcion', 'porcentaje', 'periodo_id', 'profesor_id'];

    // Relación con Materia
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'materia_has_competencia', 'competencia_id', 'materia_id');
        
    }
    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    public function profesor()
    {
        return $this->belongsTo(Usuario::class);
    }

}
