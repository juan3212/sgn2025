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
        'actividad_id',
        'valor'
    ];

    // Relación con Estudiante (Usuario)
    public function estudiante() {
        return $this->belongsTo(Usuario::class, 'estudiante_id');
    }

    // Relación con Materia
    public function actividades(){
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }
}
