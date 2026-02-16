<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChangePasswordImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $usuario = Usuario::where('nuip', $row['nuip'])->first();
            if ($usuario) {
                $usuario->password_hash = Hash::make($row['password']);
                $usuario->save();
            }
        }
    }
}
