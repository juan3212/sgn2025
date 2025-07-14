<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Permission::create(['name'=> 'ver materias']);
        Permission::create(['name' => 'ver notas']);
        Permission::create(['name' => 'administrar usuarios']);
        Permission::create(['name' => 'administrar roles']);
        Permission::create(['name' => 'administrar permisos']);
        Permission::create(['name' => 'administrar grados']);
        Permission::create(['name' => 'administrar grupos']);
        Permission::create(['name' => 'administrar actividades']);
        Permission::create(['name' => 'administrar notas']);
        Permission::create(['name'=> 'administrar estudiantes']);
        Permission::create(['name' => 'administrar competencias']);
        Permission::create(['name' => 'administrar materias']);
        Permission::create(['name' => 'administrar periodos']);

        Role::create(['name' => 'profesor'])
        ->givePermissionTo([
            'administrar grados','administrar grupos','administrar actividades','administrar notas', 'administrar competencias', 'administrar materias'
        ]);
        Role::create(['name' => 'estudiante'])
        ->givePermissionTo([
            'ver materias', 'ver notas'
        ]);
        Role::create(['name' => 'padre'])
        ->givePermissionTo([
            'administrar estudiantes'
        ]);
        Role::create(['name' => 'Super-Admin']);
        
    }
}
