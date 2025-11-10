<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioInfoDocumento extends Model
{
    //
    protected $table = 'usuario_info_documento';
    protected $fillable = [
        'usuario_id',
        'numero_documento',
        'tipo_documento',
        'departamento_expedicion',
        'municipio_expedicion',
        'documento_path',
    ];
}
