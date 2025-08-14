<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actividad;
use App\Models\Usuario;
use App\Models\Nota;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Materia;
use App\Services\NotaFinalService;

class notasActividadesController extends Controller
{
    //
    public $materia_id;
    public $competencia_id;
    public $grado;
    public $grupo;
    public $actividades;
    public $materia;

    public function getNotasEstudiantes()
    {
        $this->getActividades();

        $actividades = Actividad::whereIn('id', $this->actividades['actividades_id'])->get();

        $estudiantes = Usuario::select('usuarios.id', 'usuarios.nombre', 'usuarios.apellido')
            ->join('usuario_grado', function ($join) {
                $join->on('usuarios.id', '=', 'usuario_grado.usuario_id');
            })
            ->where('usuario_grado.grado_id', $this->grado)
            ->where('usuario_grado.grupo_id', $this->grupo)
            ->get();

        $resultados = collect();
        foreach ($estudiantes as $estudiante) {
            $actividadesEstudiante = collect();
            foreach ($actividades as $actividad) {
                $nota = Nota::where('estudiante_id', $estudiante->id)
                            ->where('actividad_id', $actividad->id)
                            ->first();
                $actividadesEstudiante->push([
                    'actividad_id'  => $actividad->id,
                    'actividad'     => $actividad->nombre,
                    'valor'         => $nota->valor ?? 0
                ]);
            }
            $promedio = $actividadesEstudiante->avg('valor');
            $resultados->push([
                'estudiante_id' => $estudiante->id,
                'nombre'        => $estudiante->nombre,
                'apellido'      => $estudiante->apellido,
                'actividades'   => $actividadesEstudiante,
                'promedio'      => round($promedio, 2)
            ]);
        }

        return $resultados;
    }
    public function getMateria(){
        $materia = Materia::select('base_materia.nombre_materia as nombre', 'grados.grado', 'grupos.grupo')
        ->join('base_materia', 'materias.materia_id', 'base_materia.id')
        ->join('grados', 'materias.grado_id', 'grados.id')
        ->join('grupos', 'materias.grupo_id', 'grupos.id')
        ->where('materias.id', $this->materia_id)
        ->first();
     
        $this->materia = $materia->nombre.' '.$materia->grado.' '.$materia->grupo;
    }
    public function getActividades(){
     
        $actividades = Actividad::select('actividades.*', 'materias.grado_id', 'materias.grupo_id')
        ->join('materias', 'actividades.materia_id', 'materias.id')
        ->where('actividades.materia_id', $this->materia_id)
        ->where('actividades.competencia_id', $this->competencia_id)
        ->get();
        
        $this->grado = $actividades->first()->grado_id;
        $this->grupo = $actividades->first()->grupo_id;
        $this->actividades = [
            'actividades_nombre' => $actividades->unique('id')->pluck('nombre')->toArray(),
            'actividades_id' => $actividades->unique('id')->pluck('id')->toArray()
        ];

    }

    public function saveNotasActividades(Request $request)
    {
        
            $validator = Validator::make($request->all(), [
            'notas' => 'required|array',
            'notas.*.estudiante_id' => 'required|integer',
            'notas.*.actividad_id' => 'required|integer',
            'notas.*.valor' => 'required|numeric|between:0,10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación en los datos proporcionados',
                'error_type' => 'validation',
                'errors' => $validator->errors(),
                'data' => $request->all()
            ], 422);
        }

        DB::beginTransaction();

        try{
            
            
            foreach ($request->notas as $nota) {
                DB::table('notas')->updateOrInsert([
                    'estudiante_id' => $nota['estudiante_id'],
                    'actividad_id' => $nota['actividad_id'],
                ], [
                    'valor' => $nota['valor'],
                ]);

                $save = new NotaFinalService($nota['valor'], $nota['actividad_id'], $nota['estudiante_id']);
                $save->updateNotaFinal();
            }
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Notas guardadas exitosamente',
                'count' => count($request->notas)
            ]);
            
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar las notas',
                'error_type' => 'server',
                'errors' => $e->getMessage(),
                'data' => $request->all()
            ], 500);
        }
    
    }

    public function render($materia_id, $competencia_id)
    {
        $this->materia_id = $materia_id;
        $this->competencia_id = $competencia_id;
        $this->getMateria();
        $estudiantesNotas = $this->getNotasEstudiantes();
        return view('notasActividades', [
            'estudiantesNotas' => $estudiantesNotas,
            'actividades' => $this->actividades,
            'materia' => $this->materia,
            ]);
    }
}
