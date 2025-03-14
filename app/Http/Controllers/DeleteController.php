<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Exception;

class DeleteController extends Controller
{
    //
    public function delete(Request $request)
    {
        $request->validate([
            'controller' => 'required|string|regex:/^[A-Za-z0-9]+$/',  // Solo permite caracteres alfanuméricos
            'functionName' => 'required|string|regex:/^[A-Za-z0-9]+$/',
            'id' => 'required',
        ]);
        
        $controllerName = $request->controller;
        $functionName = $request->functionName;
        $id = $request->id;
        
        // Lista blanca de controladores permitidos para mayor seguridad
        $allowedControllers = [
            'Materias', 'Grados', 'Usuarios', 'Competencias', 'CompetenciasService'
            // Añade otros controladores permitidos
        ];
        
        if (!in_array($controllerName, $allowedControllers)) {
            return response()->json(['error' => 'Controlador no autorizado'], 403);
        }
        
        $controllerClass = 'App\\Http\\Controllers\\' . $controllerName . 'Controller';
        
        try {
            if (!class_exists($controllerClass)) {
                return response()->json(['error' => 'Controlador no encontrado'], 404);
            }
            
            $controllerInstance = app()->make($controllerClass); // Usa el contenedor de servicios
            
            if (!method_exists($controllerInstance, $functionName)) {
                return response()->json(['error' => 'Método no encontrado'], 404);
            }
            
            // Registrar la acción de eliminación
           // Log::info("Eliminación: {$controllerName}.{$functionName} ID:{$id} por Usuario:" . auth()->id());
            
            $result = $controllerInstance->$functionName($id);
            return response()->json(['success' => 'Eliminado correctamente', 'data' => $result]);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Recurso no encontrado'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No autorizado para eliminar este recurso'], 403);
        } catch (Exception $e) {
            Log::error("Error en eliminación genérica: " . $e->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error al eliminar'], 500);
        }
    }

    public function bulkDelete(Request $request){
        $request->validate([
            'model' => 'required|string|regex:/^[A-Za-z0-9]+$/',
            'functionName' => 'required|string|regex:/^[A-Za-z0-9]+$/',
            'ids' => 'required|array',
            'ids.*' => 'integer|min:1',
        ]);
        $modelName = $request->model;
        $functionName = $request->functionName;
        $ids = $request->ids;

        $allowedModels = ['Materia', 'Usuario', 'Competencia', 'Grado'];
        
        if (!in_array($modelName, $allowedModels)) {
            return response()->json(['error' => 'Modelo no autorizado'], 403);
        }
        
        try {
            $modelClass = 'App\\Models\\' . $modelName;
            
            if (!class_exists($modelClass)) {
                return response()->json(['error' => 'Modelo no encontrado'], 404);
            }
            
            // Registrar la acción de eliminación masiva
            //Log::info("Eliminación masiva: {$controllerName} IDs:" . implode(',', $ids) . " por Usuario:" . auth()->id());
            
            // Realizar eliminación masiva
            $count = $modelClass::whereIn('id', $ids)->delete();
            
            return response()->json([
                'success' => "Se han eliminado {$count} elementos correctamente.",
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error en eliminación masiva: " . $e->getMessage());
            return response()->json(['error' => 'Ha ocurrido un error al eliminar los elementos'], 500);
        }
    }
}
