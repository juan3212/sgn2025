<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioArchivo extends Model
{
    //
    protected $table = 'usuario_archivos';
    protected $fillable = [
        'usuario_id',
        'nombre_archivo',
        'tipo_archivo',
        'ruta_archivo',
    ];

    
}
