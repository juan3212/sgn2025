<?php

namespace App\Services;

use App\Models\Periodo;

class getPeriodoService
{
    public function __construct()
    {
        //
    }

    public function currentPeriod()
    {
        $periodo = Periodo::where('fecha_inicio', '<=', date('Y-m-d'))
                          ->where('fecha_fin', '>=', date('Y-m-d'))
                          ->first();
        return $periodo;
    }
    public function allPeriods()
    {
        $periodos = Periodo::all();
        return $periodos;
    }
}