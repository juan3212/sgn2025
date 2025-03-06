<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsuarioGrado extends Model
{
    //
    use HasFactory;

    protected $fillable = ['usuario_id', 'grado_id', 'grupo_id'];

    protected $table = 'usuario_grado';

    // Relación con Usuario
    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }

    // Relación con Grado
    public function grado() {
        return $this->belongsTo(Grado::class);
    }

    // Relación con Grupo
    public function grupo() {
        return $this->belongsTo(Grupo::class);
    }
}
