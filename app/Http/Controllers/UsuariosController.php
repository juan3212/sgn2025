<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Yajra\DataTables\DataTables;



class UsuariosController extends Controller
{
    //
    public function userData(){

        $usuarios = Usuario::query()->select(['id', 'nombre', 'apellido', 'nuip', 'correo']);
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

}
