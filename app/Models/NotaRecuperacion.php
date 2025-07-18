<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaRecuperacion extends Model
{
    //
    protected $table = 'notas_recuperaciones';
    
    protected $fillable = [
        'estudiante_id',
        'materia_id',
        'periodo_id',
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
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id');
    }
}
