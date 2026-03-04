<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsistenciaEnfermeriaConsulta extends Model
{
    protected $table = 'asistencia_enfermeria_consulta';

    protected $fillable = [
        'datos_usuario_enfermeria_id',
        'hora_ingreso',
        'motivo_consulta',
        'procedimiento',
        'accion_tomada',
        'hora_accion',
        'seguimiento',
    ];

    public function asistenciaEnfermeria()
    {
        return $this->belongsTo(AsistenciaEnfermeria::class, 'asistencia_enfermeria_id');
    }
}
