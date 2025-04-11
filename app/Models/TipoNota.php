<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoNota extends Model
{
    //
    use HasFactory;

    protected $table = 'tipos_notas';

    protected $fillable = ['tipo'];

    // Relación con Notas

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }
}
