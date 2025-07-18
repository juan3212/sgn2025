<?php

namespace App\Http\Controllers\estudiantes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\getUserDataService;
use App\Models\Materia;
use App\Models\NotaFinalMateria;
use App\Models\NotaFinalCompetencia;
use Illuminate\Support\Facades\DB;
class boletinesController extends Controller
{
    //
    public $estudianteID;
    public $user;
    public $date;
    public $periodo;
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

    public function render($estudianteID)
    {
        $this->estudianteID = $estudianteID;
        $user = $this->getStudentData();
        $date = $this->getDate();
        return view('pages.estudiantes.boletines', [
            'user' => $user,
            'date' => $date,
            ]);
    }
}
