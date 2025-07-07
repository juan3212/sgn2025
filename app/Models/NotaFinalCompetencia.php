<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaFinalCompetencia extends Model
{
    //
    protected $table = 'notas_finales_competencias';
    protected $fillable = [
        'estudiante_id',
        'materia_id',
        'competencia_id',
        'nota_final'
    ];
    public $timestamps = true;

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }
    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'competencia_id');
    }

}
