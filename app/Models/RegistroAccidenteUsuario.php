<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroAccidenteUsuario extends Model
{
    protected $table = 'registro_accidente_usuario';

    protected $fillable = [
        'datos_usuario_enfermeria_id',
        'acudiente',
        'parentezco',
        'eps',
        'seguridad_social',
        'quien_atiende',
        'fecha_accidente',
        'uso_sustancias',
        'lugar_accidente',
        'numero_ruta',
        'lugar_atencion',
        'actividad_realizada',
        'mecanismo',
        'naturaleza_lesion',
        'parte_afectada',
        'descripcion',
    ];

    public function registroAccidente()
    {
        return $this->belongsTo(RegistroAccidente::class, 'registro_accidente_id');
    }

    public function datoUsuario()
    {
        return $this->belongsTo(DatoUsuario::class, 'dato_usuario_id');
    }
}
