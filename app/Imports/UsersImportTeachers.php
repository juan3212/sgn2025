<?php

namespace App\Imports;

use App\Models\Usuario;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class UsersImportTeachers implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
        foreach ($row as $key => $value) {
            if($value == null){
                return null;
            }
            $usuario = Usuario::updateOrCreate(
                ['nuip' => $row['nuip']],
                [
                    'nombre' => $row['nombre'],
                    'apellido' => $row['apellido'],
                    'correo' => $row['correo'],
                    'password_hash' => Hash::make($row['password']),
                ]
            );
            $usuario->assignRole('profesor');
            $usuario->save();
        }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $e->getMessage();
        }
    }
}
