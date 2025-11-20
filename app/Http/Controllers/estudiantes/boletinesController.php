<?php

namespace App\Http\Controllers\estudiantes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\getUserDataService;
use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\NotaFinalCompetencia;
use App\Models\Periodo;
use Illuminate\Support\Facades\DB;
class boletinesController extends Controller
{
    //
    public $estudianteID;
    public $user;
    public $date;
    public function getStudentData()
    {
        $user = new getUserDataService;
        $user = $user->getUserDataFromID($this->estudianteID);
        return $user;
    }

    public function getDate()
    {
        $date = date('d F Y');
        return $date;
    }

    public function getPeriodo()
    {
        $periodo = Periodo::where('fecha_fin', '>', now())
        ->first();
        $periodo->id -= 1;
        if(date("j-n") >= "19-11"){
            $periodo->id += 1;
        }
        switch (true){
            case $periodo->id == 1:
                return 'I';
            case $periodo->id == 2:
                return 'II';
            case $periodo->id == 3:
                return 'III';
            case $periodo->id == 4:
                return 'IV';
            default:
                return 'I';
        }
    }
    public function render($estudianteID)
    {
        $this->estudianteID = $estudianteID; 
        $user = $this->getStudentData();
        $date = $this->getDate();
        $periodo = $this->getPeriodo();
        return view('pages.estudiantes.boletines', [
            'user' => $user,
            'date' => $date,
            'estudianteID' => $estudianteID,
            'periodo' => $periodo,
            ]);
    }
}
