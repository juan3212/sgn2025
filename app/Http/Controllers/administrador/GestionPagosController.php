<?php

namespace App\Http\Controllers\administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\UsuarioEstadoPago;
use Yajra\DataTables\DataTables;

class GestionPagosController extends Controller
{
    
    public function cambiarEstadoPago($id)
    {
        try {
            $estudiante = UsuarioEstadoPago::where('usuario_id', $id)->first();
            if($estudiante->estado_pago == 'si'){
                $estudiante->estado_pago = 'no';
            }else{
                $estudiante->estado_pago = 'si';
            }
            $estudiante->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function data()
    {
        $estudiantes = Usuario::select('usuarios.id', 'usuarios.nombre', 'usuarios.apellido', 'usuarios.nuip', 'usuario_estado_pago.estado_pago')
        ->join('model_has_roles', 'model_has_roles.model_id', '=', 'usuarios.id')
        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
        ->join('usuario_estado_pago', 'usuarios.id', '=', 'usuario_estado_pago.usuario_id')
        ->where('roles.name', 'estudiante');
        return datatables()->of($estudiantes)
        ->addColumn('action', function ($estudiante) {
            if($estudiante->estado_pago == 'si'){
                return '<button type="button" data-id="' . $estudiante->id . '" class="change-state bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Desactivar</button>';
            }else{
                return '<button type="button" data-id="' . $estudiante->id . '" class="change-state bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Activar</button>';
            }
        })
        ->make(true);
    }
}
