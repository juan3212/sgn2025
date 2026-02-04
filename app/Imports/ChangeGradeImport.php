<?php

namespace App\Imports;

use App\Models\Usuario;
use App\Models\UsuarioGrado;
use App\Models\Grado;
use App\Models\Grupo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChangeGradeImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $grados = Grado::all();
        $grupos = Grupo::all();

        foreach ($row as $key => $value) {
            $grado = $row["grado"];
            $grupo = $row["grupo"];
            $newGrado = $grados->where("grado", $grado)->first();
            $newGrupo = $grupos->where("grupo", $grupo)->first();
            $nuip = $row["nuip"];
            $usuario = Usuario::select("id")->where("nuip", $nuip)->first();
            $usuarioGrado = UsuarioGrado::where(
                "usuario_id",
                $usuario->id,
            )->first();
            $usuarioGrado->update([
                "grado_id" => $newGrado->id,
                "grupo_id" => $newGrupo->id,
            ]);
            $usuarioGrado->save();
        }
    }
}
