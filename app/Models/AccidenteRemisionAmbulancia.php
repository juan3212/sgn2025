<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccidenteRemisionAmbulancia extends Model
{
    protected $table = 'accidente_remision_ambulancia';

    protected $fillable = [
        'registro_accidente_id',
        'hora_de_llamada',
        'quien_atiende',
        'instrucciones_recibidas',
        'numero_movil',
        'hora_llegada_movil',
        'entidad_remitida',
        'canal_atencion',
    ];

    public function registroAccidenteUsuario()
    {
        return $this->belongsTo(RegistroAccidenteUsuario::class, 'registro_accidente_usuario_id');
    }
}
