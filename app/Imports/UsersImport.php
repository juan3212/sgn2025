<?php

namespace App\Imports;

use App\Models\Usuario;
use App\Models\Grado;
use App\Models\Grupo;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Role;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = Usuario::firstOrCreate(
            ['nuip' => $row['nuip']],
            [
                'nombre' => $row['nombre'],
                'apellido' => $row['apellido'],
                'correo' => $row['correo'],
                'password_hash' => Hash::make($row['password']),
            ]
        );

        if ($user->wasRecentlyCreated && !empty($row['role'])) {
            $role = Role::where('name', $row['role'])->first();
            if ($role) {
                $user->assignRole($role);

                if ($role->name === 'estudiante' && !empty($row['grado']) && !empty($row['grupo'])) {
                    $grado = Grado::where('grado', $row['grado'])->first();
                    $grupo = Grupo::where('grupo', $row['grupo'])->first();

                    if ($grado && $grupo) {
                        $user->grados()->attach($grado->id, ['grupo_id' => $grupo->id]);
                    }
                }
            }
        }

        return $user;
    }
}
