<?php

namespace App\Http\Controllers\administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesyPermisosController extends Controller
{
    //
    public function rolesTable()
    {
        $roles = Role::where('name', '!=', 'Super-Admin')->get();
        return datatables()->of($roles)
        ->addColumn('permissions', function ($roles) {
            return $roles->permissions->pluck('name')->implode(', ');
        })
        ->addColumn('action', function ($roles) {
            return '<a href="' . route('create-role', $roles) . '" class="change-state bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</a>';
        })
        ->addColumn('delete', function ($roles) {
            return '<button class="delete bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" data-id="' . $roles->id . '">Eliminar</button>';
        })
        ->rawColumns(['action', 'delete'])
        ->make(true);
    }

    public function permissionTable()
    {
        $permissions = Permission::all();
        return datatables()->of($permissions)
        ->addColumn('delete', function ($permissions) {
            return '
            <div class="flex justify-center">
            <button class="delete bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" data-id="' . $permissions->id . '">Eliminar</button>
            </div>';
        })
        ->rawColumns(['delete'])
        ->make(true);
    }
}
