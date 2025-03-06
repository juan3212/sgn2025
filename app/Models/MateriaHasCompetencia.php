<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MateriaHasCompetencia extends Model
{
    //
    use HasFactory;
    protected $table = 'materia_has_competencia';
    protected $fillable = ['materia_id', 'competencia_id'];
}
