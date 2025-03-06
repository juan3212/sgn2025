<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Spatie\Permission\Models\Role;


class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $usuario =
            [
                "nombre" => "Juan Pablo",
                "apellido" => "Acevedo Hernandez",
                "nuip" => 1114088015,
                "correo" => "juanpabloacevedo3212@gmail.com",
                "password_hash" => Hash::make("jp@231103")
        ];
        
        Usuario::create($usuario);

        $usuario = Usuario::find(1); // Encuentra un usuario existente (por ejemplo, ID 1)
        $rolAdministrador = Role::where('name', 'administrador')->first(); // Obtiene el rol 'administrador' de Spatie
        
        $usuario->assignRole($rolAdministrador);
       
    }
}
