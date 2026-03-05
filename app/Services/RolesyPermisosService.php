<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesyPermisosService
{
    public function __construct()
    {
        //
    }

    public function createRoles(array $request)
    {
        validator($request, [
            "name" => "required|string|unique:roles,name",
            "permissions" => "required|array|min:1",
        ])->validate();

        try {
            Role::create([
                "name" => $request["name"],
            ])->givePermissionTo($request["permissions"]);

            return response()->json([
                "success" => true,
                "message" => "Role created successfully",
            ]);
        } catch (\Exception $e) {
            dump($e->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Role not created",
            ]);
        }
    }

    public function createPermissions(array $request)
    {
        validator($request, [
            "name" => "required|string",
        ])->validate();

        $guard_name = $request["guard_name"] ?? "web";
        $name = $request["name"];

        Permission::create([
            "name" => $name,
            "guard_name" => $guard_name,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Permission created successfully",
        ]);
    }

    public function updateRole(array $request)
    {
        /* Validator::make($request, [
             'role' => 'required|string',
             'name' => 'required|string|unique:roles,name',
             'permissions' => 'required|array|min:1',
         ])->validate();*/

        $role = Role::findByName($request["role"]);
        $role->name = $request["name"];
        $role->syncPermissions($request["permissions"]);
        $role->save();

        return response()->json([
            "success" => true,
            "message" => "Permissions updated successfully",
        ]);
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);
        $role->delete();

        return response()->json([
            "success" => true,
            "message" => "Role deleted successfully",
        ]);
    }

    public function deletePermission($id)
    {
        $permission = Permission::find($id);
        $permission->delete();

        return response()->json([
            "success" => true,
            "message" => "Permission deleted successfully",
        ]);
    }
}
