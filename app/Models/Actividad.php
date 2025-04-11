<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    //
    protected $table = 'actividades';
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_nota',
        'materia_id',
        'competencia_id',
        'periodo_id',
    ];

    public function tipoNota()
    {
        return $this->belongsTo(TipoNota::class, 'tipo_nota');
    }
    public function competencia()
    {
        return $this->belongsTo(Competencia::class);
    }
    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
