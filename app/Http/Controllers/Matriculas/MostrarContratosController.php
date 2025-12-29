<?php

namespace App\Http\Controllers\Matriculas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MostrarContratosController extends Controller
{
    //
    public function mostrar($tipo, $estudianteId)
    {
        $this->validatePermission($estudianteId);

         $grados = DB::table('grados')
        ->select('grados.*')
        ->get();
        $estudiante = DB::table('usuarios')
        ->select('*')
        ->join('usuario_grado', 'usuario_grado.usuario_id', 'usuarios.id')
        ->join('grados', 'grados.id', 'usuario_grado.grado_id')
        ->where('usuarios.id', $estudianteId)
        ->first();
        $padres = DB::table('usuario_has_child')
        ->select('*')
        ->where('child_id', $estudianteId)
        ->join('usuarios', 'usuarios.id', 'usuario_has_child.parent_id')
        ->leftJoin('usuario_contacto', 'usuario_contacto.usuario_id', 'usuarios.id')
        ->get();
        $acudienteFacturacion = DB::table('usuario_facturacion')
        ->select('*')
        ->where('estudiante_id', $estudianteId)
        ->join('usuarios', 'usuarios.id', 'usuario_facturacion.acudiente_id')
        ->leftJoin('usuario_contacto', 'usuario_contacto.usuario_id', 'usuarios.id')
        ->first();
        $valores = DB::table('usuario_valores_matricula_pension')
        ->select('*')
        ->where('usuario_id', $estudianteId)
        ->first();
        $promovido = DB::table('usuarios_promovidos')
        ->select('*')
        ->where('usuario_id', $estudianteId)
        ->first();

        $padre = $padres->where('parentesco', 'Padre')->first();
        $madre = $padres->where('parentesco', 'Madre')->first();
        $padreMadre = $padres->where('parentesco', 'Padre/Madre_cabeza_de_familia')->first();

        $datos = [
            'studentName' => $estudiante->nombre.' '.$estudiante->apellido,
            'studentGrade' => isset($promovido) ? $grados->where('id', $promovido->grado_destino)->first()->grado : $grados->where('id', $estudiante->grado_id +1)->first()->grado,
            'matricula' => number_format($valores->valor_matricula, 0, ',', '.'),
            'pension' => number_format($valores->valor_pension, 0, ',', '.'),
            'fatherName' => isset($padreMadre) ? $padreMadre->nombre.' '.$padreMadre->apellido : $padre->nombre.' '.$padre->apellido,
            'fatherCc' => isset($padreMadre) ? $padreMadre->nuip : $padre->nuip,
            'fatherEmail' => isset($padreMadre) ? $padreMadre->email : $padre->email,
            'fatherPhone' => isset($padreMadre) ? $padreMadre->telefono : $padre->telefono,
            'motherName' => isset($padreMadre) ? $padreMadre->nombre.' '.$padreMadre->apellido : $madre->nombre.' '.$madre->apellido,
            'motherCc' => isset($padreMadre) ? $padreMadre->nuip : $madre->nuip,
            'motherEmail' => isset($padreMadre) ? $padreMadre->email : $madre->email,
            'motherPhone' => isset($padreMadre) ? $padreMadre->telefono : $madre->telefono,
            'parentName' => $acudienteFacturacion->nombre.' '.$acudienteFacturacion->apellido,
            'parentId' => $acudienteFacturacion->nuip,
            'parentIdCity' => 'Bogotá',
        'studentName' => $estudiante->nombre.' '.$estudiante->apellido,
        'studentGrade' => isset($promovido) ? $grados->where('id', $promovido->grado_destino)->first()->grado : $grados->where('id', $estudiante->grado_id +1)->first()->grado,
        'billedName' => $acudienteFacturacion->nombre.' '.$acudienteFacturacion->apellido, // Nombre de la persona a quien se facturará
        'billedId' => $acudienteFacturacion->nuip,
        'billedEmail' => $acudienteFacturacion->email,
        'billedAddress' => $acudienteFacturacion->direccion,
        'billedPhone' => $acudienteFacturacion->telefono,
        ];

        return view('matriculas.mostrar-contrato', [
            'tipo' => $tipo, 
            'datos' => $datos
        ]);
    }

    private function validatePermission($estudianteId){
        $usuarioActual = Auth::user();
        if(!$usuarioActual->hasRole(['administrador', 'profesor', 'Super-Admin']) && $usuarioActual->id != $estudianteId){
            abort(404, "Archivo no encontrado");
        }
        return true;
    }
    
}
