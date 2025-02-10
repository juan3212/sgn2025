<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = ['administrador', 'profesor', 'estudiante', 'padre'];

        foreach ($roles as $role) {
            Rol::insert([
                'rol' => $role,
            ]);
        }
    }
}
