<?php

namespace App\Http\Controllers\administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GestionPagosController extends Controller
{
    
    public function cambiarEstadoPago($id, $tipo)
    {
        $db;
        $column;
        $estado_pago;
        if($tipo == 'pension'){
            $db = "usuario_estado_pago";
            $column = "usuario_id";
        }else{
            $db = "pago_matricula";
            $column = "user_id";
        }
        try {
            $estudiante = DB::table($db)->where($column, $id)->first();
            if($estudiante->estado_pago == 'si'){
                $estado_pago = 'no';
            }else{
                $estado_pago = 'si';
            }
            DB::table($db)->where($column, $id)->update([
                'estado_pago' => $estado_pago
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function data()
    {
        $estudiantes = Usuario::select('usuarios.id', 'usuarios.nombre', 'usuarios.apellido', 'usuarios.nuip', 'usuario_estado_pago.estado_pago as pension', 'pago_matricula.estado_pago as matricula')
        ->join('model_has_roles', 'model_has_roles.model_id', '=', 'usuarios.id')
        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
        ->join('usuario_estado_pago', 'usuarios.id', '=', 'usuario_estado_pago.usuario_id')
        ->join('pago_matricula', 'usuarios.id', '=', 'pago_matricula.user_id')
        ->where('roles.name', 'estudiante');
        return datatables()->of($estudiantes)
        ->addColumn('pension', function ($estudiante) {
            if($estudiante->pension == 'si'){
                return '<button type="button" data-id="' . $estudiante->id . '" data-tipo="pension" class="change-state bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Desactivar</button>';
            }else{
                return '<button type="button" data-id="' . $estudiante->id . '" data-tipo="pension" class="change-state bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Activar</button>';
            }
        })
        ->addColumn('matricula', function ($estudiante) {
            if($estudiante->matricula == 'si'){
                return '<button type="button" data-id="' . $estudiante->id . '" data-tipo="matricula" class="change-state bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Desactivar</button>';
            }else{
                return '<button type="button" data-id="' . $estudiante->id . '" data-tipo="matricula" class="change-state bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Activar</button>';
            }
        })
        ->rawColumns(['pension', 'matricula'])
        ->make(true);
    }
}
