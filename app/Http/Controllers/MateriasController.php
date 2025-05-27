<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Materia;
use App\Models\Usuario;
use App\Http\Controllers\Estudiantes\calcularNotasController;
use App\View\Components\progressBar;
use Illuminate\Support\Facades\Blade;

class MateriasController extends Controller
{
    //

    public $user;
    public $isAdmin;
    public $isTeacher;
    public $grado;
    public $grupo;
    public $calcularNotasController;

    public $d;
    

    public function __construct(calcularNotasController $calcularNotasController)
    {
        $this->calcularNotasController = $calcularNotasController;
        $this->getUserData();
    }

    public function getUserData()
    {
        $user = Auth::user();
        $this->user = $user; // Almacenar el objeto usuario para usos potenciales
        $this->isAdmin = $user->hasRole('Super-Admin');
        $this->isTeacher = $user->hasRole('profesor');

        if (!$this->isAdmin) {
            // Para usuarios no administradores, cargar su grado y grupo específicos
            $userData = Usuario::with('grados', 'grupos')->find($user->id);
            if ($userData && $userData->grados->isNotEmpty() && $userData->grupos->isNotEmpty()) {
                $this->grado = $userData->grados[0]->id;
                $this->grupo = $userData->grupos[0]->id;
            }
        }
    }

    public function loadData()
    {
        $this->getUserData(); // Asegurarse de que los datos del usuario (incluyendo isAdmin) estén cargados

        $query = Materia::select('materias.id', 'base_materia.nombre_materia', 'materias.intensidad_horaria', 'usuarios.nombre', 'usuarios.apellido', 'grados.grado', 'grupos.grupo')
            ->join('base_materia', 'materias.materia_id', '=', 'base_materia.id')
            ->join('usuarios', 'materias.profesor_id', '=', 'usuarios.id')
            ->join('grados', 'materias.grado_id', '=', 'grados.id')
            ->join('grupos', 'materias.grupo_id', '=', 'grupos.id');

        if($this->isTeacher){
            $query->where('materias.profesor_id', $this->user->id);
            $materias = $query->get();
            return $materias;
        }

        if(!$this->isAdmin){
            // Solo aplicar filtros si el usuario no es admin y tiene grado/grupo definidos
            if (isset($this->grado) && isset($this->grupo)) {
                $query->where('materias.grado_id', $this->grado)
                      ->where('materias.grupo_id', $this->grupo);
            }
        }
        
        $materias = $query->get();
        return $materias;
    }

    public function data(){
        
        $materias = $this->loadData();
    
        return datatables()->of($materias)
            ->addColumn('checkbox', function ($materia) {
                return '<input type="checkbox" class="select-checkbox form-checkbox h-5 w-5 text-blue-600" data-id="' . $materia->id . '">';
            })
            ->addColumn('action', function ($materia) {
                return '<a class="btn btn-xs btn-primary edit" href="/edit/materias/'.$materia->id.'">Edit</a>
                        <button class="btn btn-xs btn-danger delete" data-id="'.$materia->id.'">Delete</button>';
            })
            ->addColumn('notas', function ($materia) {
                $notas =  $this->calcularNotasController->calcularNotasMateria(['materia' => $materia->id, 'estudiante' => $this->user->id]);
                
                $graficoNotasComponent = new progressBar(nota: $notas, notaMaxima: 10.0); // Asume nota máxima de 10
                return Blade::renderComponent($graficoNotasComponent);
            })
            ->rawColumns(['checkbox', 'action', 'notas'])
            ->make(true);
    }

    public function delete($id){
        $materia = Materia::find($id);
        $materia->delete();
        return response()->json(['success' => 'Materia eliminada exitosamente.']);
    }
}
