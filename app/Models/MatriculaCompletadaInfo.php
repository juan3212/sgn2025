<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatriculaCompletadaInfo extends Model
{
    //
    protected $table = "matricula_completada_info";
    protected $fillable = ["estudiante_id", "ip"];
}
