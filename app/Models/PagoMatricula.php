<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoMatricula extends Model
{
    protected $table = 'pago_matricula';
    protected $fillable = [
        'usuario_id',
        'estado_pago',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
