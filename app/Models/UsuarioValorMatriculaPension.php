<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioValorMatriculaPension extends Model
{
    protected $table = 'usuario_valores_matricula_pension';
    protected $fillable = [
        'usuario_id',
        'valor_matricula',
        'valor_pension',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
