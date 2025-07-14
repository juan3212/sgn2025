<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competencia;
use App\Models\Materia;
use App\Models\Periodo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompetenciasServiceController extends Controller
{
    //
    public function getSubjects($search, $teacher_id= null)
    {
        $query = Materia::selectRaw('materias.id as id, CONCAT(nombre_materia, " - ", grado, " - ", grupo) as nombre')
            ->join('base_materia', 'materias.materia_id', '=', 'base_materia.id')
            ->join('grados', 'materias.grado_id', '=', 'grados.id')
            ->join('grupos', 'materias.grupo_id', '=', 'grupos.id');
            if($teacher_id) {
                $query->where('materias.profesor_id', $teacher_id);
            }
            $query = $query->where('nombre_materia', 'like', '%' . $search . '%')
            ->get();
            return $query;
    }
    public function getPeriodo()
    {
        return Periodo::all();
    }

    public function detachMateria(Array $request)
    {
        try {

            $competencia = Competencia::findOrFail($request['competencia_id']);
            $competencia->materias()->detach($request['materia_id']);
            
            
            return response()->json([
                'success' => true,
                'message' => 'Materias eliminadas con éxito'
            ]);

            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar las materias'
            ], 500);
        }
    }

    public function update($requestData)
    {
        try {
            // Validar los datos
            $validator = Validator::make($requestData, [
                'id' => 'required|integer',
                'nombre' => 'required|string',
                'descripcion' => 'required|string',
                'porcentaje' => 'required|numeric',
                'periodo_id' => 'required|integer',
                'materias' => 'nullable|array',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            DB::beginTransaction();
    
            // Actualizar la competencia
            $competencia = Competencia::findOrFail($requestData['id']);
            $competencia->nombre = $requestData['nombre'];
            $competencia->descripcion = $requestData['descripcion'];
            $competencia->porcentaje = $requestData['porcentaje'];
            $competencia->periodo_id = $requestData['periodo_id'];
            $competencia->save();
    
            // Sincronizar las materias
            if (count($requestData['materias'])>0) {
                $competencia->materias()->syncWithoutDetaching($requestData['materias']);
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Competencia actualizada con éxito',
                'data' => $competencia,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la competencia',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
