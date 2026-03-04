<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsistenciaEnfermeriaDatoUsuario extends Model
{
    protected $table = 'asistencia_enfermeria_dato_usuario';

    protected $fillable = [
        'nombre',
        'apelllido',
        'tipo_documento',
        'nuip',
        'edad',
        'grado',
    ];

    public function asistenciaEnfermeria()
    {
        return $this->belongsTo(AsistenciaEnfermeria::class, 'asistencia_enfermeria_id');
    }

    public function datoUsuario()
    {
        return $this->belongsTo(DatoUsuario::class, 'dato_usuario_id');
    }
}
