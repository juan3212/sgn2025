<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioFacturacion extends Model
{
    //
    protected $table = 'usuario_facturacion';
    protected $fillable = [
        'estudiante_id',
        'acudiente_id',
    ];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function acudiente()
    {
        return $this->belongsTo(User::class, 'acudiente_id');
    }
}
