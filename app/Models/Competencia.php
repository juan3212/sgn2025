<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Competencia extends Model
{
    //
    use HasFactory;

    protected $table = 'competencias';

    protected $fillable = ['nombre', 'descripcion', 'periodo_id'];

    // Relación con Materia
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'materia_has_competencia', 'competencia_id', 'materia_id')
                    ->withPivot('periodo_id'); // Si necesitas acceder al campo periodo_id
    }
    public function notas(){
        return $this->belongsTo(Nota::class);
    }
}
