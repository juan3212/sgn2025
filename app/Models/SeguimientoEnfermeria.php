<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoEnfermeria extends Model
{
    protected $table = 'seguimiento_enfermeria';

    protected $fillable = [
        'datos_usuario_enfermeria_id',
        'numero_dia',
        'fecha',
        'responsable',
        'observaciones',
        'observaciones_finales',
    ];

    public function asistenciaEnfermeriaConsulta()
    {
        return $this->belongsTo(AsistenciaEnfermeriaConsulta::class, 'asistencia_enfermeria_consulta_id');
    }
}
