<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use App\Services\NotaFinalService;

class NotasController extends Controller
{
    use ValidatesRequests;
    //

    public $actividad_id;
    public $grupo_id;
    public $grado_id;
    
    public function getDataWithActivities($actividad_id)
    {
       $data = DB::table("actividades")
       ->join('materias', 'actividades.materia_id', '=', 'materias.id')
       ->select('actividades.id as actividad_id','actividades.descripcion', 'materias.grado_id', 'materias.grupo_id')
       ->where('actividades.id', $actividad_id)
       ->first();
        
        return $data;
    }

    public function getStudents(Request $request)
    {
        $data = $this->getDataWithActivities($request->actividad_id);
        $this->grupo_id = $data->grupo_id;
        $this->grado_id = $data->grado_id;
        $this->actividad_id = $data->actividad_id;

        $students = Usuario::leftJoin('usuario_grado', function ($join) {
            $join->on('usuarios.id', '=', 'usuario_grado.usuario_id');
        })
        ->leftJoin('notas', function ($join) {
            $join->on('usuarios.id', '=', 'notas.estudiante_id')
            ->where('notas.actividad_id', '=', $this->actividad_id);
        })
        ->where(function ($query) {
            $query->where('usuario_grado.grado_id', $this->grado_id);
        })
        ->where(function ($query) {
            $query->where('usuario_grado.grupo_id', $this->grupo_id);
        })  
        ->select('usuarios.id', 'usuarios.nombre', 'usuarios.apellido', 'usuarios.nuip', 'notas.valor') // Selecciona solo los campos de la tabla usuarios
        ->distinct() // Evita duplicados si hay múltiples coincidencias en las tablas relacionadas
        ->get();

        return $students;

    }


    public function table(Request $request)
    {
        $students = $this->getStudents($request);
        return DataTables()->of($students)
            ->addColumn('rates', function ($student) {
                return '<span contenteditable="true" class="editable-cell" data-id="'.$student->id.'">' . $student->valor.'</span>';
            })
            ->rawColumns(['rates'])
            ->make(true);
    }

    public function save(Request $request)
    {

        
        try {
            if (!$request->has('notas') || !$request->has('actividad_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se proporcionaron datos de notas',
                    'error_type' => 'validation'
                ], 400);
            }


            $this->actividad_id = $request->actividad_id;
            $notasData = $request->notas;
            
            // Validación de todas las notas antes de procesar
            foreach ($notasData as $nota) {
                $validator = Validator::make(['nota' => $nota], rules: [
                    'nota.id' => 'required',
                    'nota.valor' => 'required|numeric|between:0,10',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error de validación en los datos proporcionados',
                        'error_type' => 'validation',
                        'errors' => $validator->errors(),
                        'data' => $nota
                    ], 422);
                }
            }

            DB::beginTransaction();
            try {
                foreach ($notasData as $nota) {
                
                        DB::table('notas')->updateOrInsert([
                            'estudiante_id' => $nota['id'],
                            'actividad_id' => $this->actividad_id],
                            [
                                'valor' => $nota['valor'],
                            ]
                        );
                
                        $save = new NotaFinalService($nota['valor'], $this->actividad_id, $nota['id']);
                        $save->updateNotaFinal();
                    
                }
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Notas guardadas exitosamente',
                    'count' => count($notasData)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar las notas en la base de datos',
                'error_type' => 'database',
                'error_details' => $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error_type' => 'server',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    public function render ($actividad_id)
    {
        return view('notas', ['actividad_id'=>$actividad_id]);
        
    }
}

