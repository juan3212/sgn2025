<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Periodo extends Model
{
    //
    use HasFactory;

    protected $table = 'periodos';

    protected $fillable = ['periodo', 'fecha_inicio', 'fecha_fin'];

    // Relación con Competencias
    public function competencias() {
        return $this->hasMany(Competencia::class);
    }
    // Relación con Materias
    public function materias() {
        return $this->hasMany(MateriaHasCompetencia::class);
    }

   public function actividades()
   {
       return $this->hasMany(Actividad::class);
   }
}
