<?php

namespace App\Http\Controllers\estudiantes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class boletinesController extends Controller
{
    //
    public $estudianteID;

    public function __construct($estudianteID)
    {
        $this->estudianteID = $estudianteID;
    }

    public function getStudentData()
    {
        $user = Auth::user();
        $this->user = $user; // Almacenar el objeto usuario para usos potenciales
        $this->isAdmin = $user->hasRole('Super-Admin');
        $this->isTeacher = $user->hasRole('profesor');
    }
    public function render()
    {
        return view('pages.estudiantes.boletin');
    }
}
