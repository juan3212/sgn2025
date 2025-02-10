<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Competencia extends Model
{
    //
    use HasFactory;

    protected $table = 'competencias';

    protected $fillable = ['nombre', 'descripcion', 'materia_id', 'periodo_id'];

    // Relación con Materia
    public function materia() {
        return $this->belongsTo(Materia::class);
    }

    // Relación con Periodo
    public function periodo() {
        return $this->belongsTo(Periodo::class);
    }
}
