<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Periodo extends Model
{
    //
    use HasFactory;

    protected $table = 'periodos';

    protected $fillable = ['periodo'];

    // Relación con Competencias
    public function competencias() {
        return $this->hasMany(Competencia::class);
    }

    // Relación con Notas
    public function notas() {
        return $this->hasMany(Nota::class);
    }
}
