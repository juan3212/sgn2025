<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class NotasController extends Controller
{
    use ValidatesRequests;
    //
    public $grado_id = 7;
    public $grupo_id = 1;
    public $actividad_id = 4;
    public $nota;
    public $usuario_id;

    public function getStudents()
    {

        $students = Usuario::leftJoin('usuario_grado', function ($join) {
            $join->on('usuarios.id', '=', 'usuario_grado.usuario_id');
        })
        ->leftJoin('notas', function ($join) {
            $join->on('usuarios.id', '=', 'notas.estudiante_id');
        })
        ->where(function ($query) {
            $query->where('usuario_grado.grado_id', $this->grado_id);
        })
        ->where(function ($query) {
            $query->where('usuario_grado.grupo_id', $this->grupo_id);
        })
        ->where(function ($query) {
            $query->where('notas.actividad_id', $this->actividad_id)
                  ->orWhereNull('notas.actividad_id'); // Para mantener el LEFT JOIN
        })
        ->select('usuarios.id', 'usuarios.nombre', 'usuarios.apellido', 'usuarios.nuip', 'notas.valor') // Selecciona solo los campos de la tabla usuarios
        ->distinct() // Evita duplicados si hay múltiples coincidencias en las tablas relacionadas
        ->get();
        return $students;

    }

    public function table()
    {
        $students = $this->getStudents();
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
            if (!$request->has('notasData')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se proporcionaron datos de notas',
                    'error_type' => 'validation'
                ], 400);
            }

            $notasData = $request->all();
            
            // Validación de todas las notas antes de procesar
            foreach ($notasData as $nota) {
                $validator = Validator::make(['nota' => $nota], [
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
                    DB::table('notas')->upsert(
                        [
                            'estudiante_id' => $nota['id'],
                            'actividad_id' => $this->actividad_id,
                            'valor' => $nota['valor']
                        ],
                        ['estudiante_id', 'actividad_id'],
                        ['valor']
                    );
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
}

