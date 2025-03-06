<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nota extends Model
{
    //
    use HasFactory;

    protected $table = 'notas';

    protected $fillable = [
        'estudiante_id',
        'materia_id',
        'periodo_id',
        'competencia_id',
        'tipo_nota_id',
        'descripcion',
        'valor'
    ];

    // Relación con Estudiante (Usuario)
    public function estudiante() {
        return $this->belongsTo(Usuario::class, 'estudiante_id');
    }

    // Relación con Materia
    public function materia() {
        return $this->belongsTo(Materia::class);
    }

    // Relación con Competencia
    public function competencia() {
        return $this->belongsTo(Competencia::class);
    }

    // Relación con Periodo
    public function periodo() {
        return $this->belongsTo(Periodo::class);
    }

    // Relación con TipoNota
    public function tipoNota() {
        return $this->belongsTo(TipoNota::class, 'tipo_nota_id');
    }
}
