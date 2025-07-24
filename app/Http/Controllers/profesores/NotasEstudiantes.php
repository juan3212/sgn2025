<?php

namespace App\Http\Controllers\profesores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MostrarNotasService;
use App\Models\Materia;
use App\Models\UsuarioGrado;
use Illuminate\Support\Facades\Blade;
use App\View\Components\progressBar;

class NotasEstudiantes extends Controller
{
    //
    public $materia;
    public $periodo;
    public $mostrarNotasService;
    public function __construct($materia = null, $periodo = null)
    {
        $this->materia = $materia;
        $this->periodo = $periodo;
        $this->mostrarNotasService = new MostrarNotasService(false);
    }
   

    public function dataOfMateria($materia)
    {
        $materia = Materia::where('id', $materia)->first();
        return $materia;
    }
    public function getEstudiantes($materiaId)
    {
        $materia = $this->dataOfMateria($materiaId);
        
        $estudiantes = UsuarioGrado::where('grado_id', $materia->grado_id)
        ->where('grupo_id', $materia->grupo_id)
        ->join('usuarios', 'usuarios.id', '=', 'usuario_grado.usuario_id')
        ->select('usuarios.id', 'usuarios.nombre', 'usuarios.apellido')
        ->get();
        return $estudiantes;
    }
    public function dataOfEstudiante(Request $request)
    {
        $estudiante = $this->getEstudiantes($request->materia);
        $this->periodo = $request->periodo;
        $this->materia = $request->materia;
        return datatables()->of($estudiante)
        ->addColumn('nota', function ($estudiante) {
            $notas =  $this->mostrarNotasService->mostrarNotasMateria($estudiante->id, $this->materia, $this->periodo);
            $notas = number_format($notas, 2, '.');
             $graficoNotasComponent = new progressBar(grade: $notas, maxGrade: 10.0);
             return Blade::renderComponent($graficoNotasComponent);
         })
        ->rawColumns(['nota'])
        ->make(true);
    }

}
