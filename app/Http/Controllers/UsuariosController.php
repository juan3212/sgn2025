<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Models\Grado;
use App\Models\Grupo;
use Spatie\Permission\Models\Role;


class UsuariosController extends Controller
{
    //

    public $grados;
    public $grupos;
    public $roles;

    public function getUserData(){
        $usersQuery = Usuario::select('usuarios.id as id', 'usuarios.nombre as nombre', 'usuarios.apellido as apellido', 'usuarios.nuip as nuip', 'usuarios.correo as correo', 'grados.grado as grado', 'grupos.grupo as grupo', 'roles.name as role')
        ->leftjoin('usuario_grado', 'usuario_grado.usuario_id', '=', 'usuarios.id')
        ->leftjoin('grados', 'grados.id', '=', 'usuario_grado.grado_id')
        ->leftjoin('grupos', 'grupos.id', '=', 'usuario_grado.grupo_id')
        ->leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'usuarios.id')
        ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id');
  
        return $usersQuery;
    }
    
    public function userData(Request $request){

        $usuarios = $this->getUserData();
        if($request->grado){
            $usuarios = $usuarios->where('grados.id', $request->grado);
        }
        if($request->grupo){
            $usuarios = $usuarios->where('grupos.id', $request->grupo);
        }
        if($request->role){
            $usuarios = $usuarios->where('roles.id', $request->role);
        }
        $datatables = new DataTables();
        return $datatables->eloquent($usuarios)
            ->addColumn('checkbox', function($usuario){
                return '<input type="checkbox" class="select-checkbox form-checkbox h-5 w-5 text-blue-600" data-id="' . $usuario->id . '">';
            })
            ->addColumn('action', function($usuario){
                return '<a href="#" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                        <a href="#" class="btn btn-xs btn-danger delete" data-id="' . $usuario->id . '"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action', 'checkbox'])
            ->toJson();
    }

    public function delete($id){
        $usuario = Usuario::find($id);
        $usuario->delete();
        return redirect()->route('dashboard');
    }

    public function importForm()
    {
        return view('usuarios.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->route('usuarios')->with('success', 'Usuarios importados correctamente.');
    }

    public function getFilterData()
    {
        $this->grados = Grado::all();
        $this->grupos = Grupo::all();
        $this->roles = Role::all();
    }

    public function render(){
        $this->getFilterData();
        return view('usuarios', [
            'grados' => $this->grados,
            'grupos' => $this->grupos,
            'roles' => $this->roles
        ]);
    }
}
