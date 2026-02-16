<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComentarioEstudiantePeriodo extends Model
{
    //
    protected $table = 'comentarios_estudiante_periodo';

    protected $fillable = [
        'estudiante_id',
        'periodo_id',
        'comentario',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }
}
