<?php

namespace App\Imports;

use App\Models\Usuario;
use App\Models\UsuarioGrado;
use App\Models\Grado;
use App\Models\Grupo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Services\CambiarGradoUsuarioService;
use App\Services\getPeriodoService;

class ChangeGradeImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $cambiarGradoUsuarioService;
    protected $getPeriodoService;

    public function __construct(CambiarGradoUsuarioService $cambiarGradoUsuarioService, getPeriodoService $getPeriodoService)
    {
        $this->cambiarGradoUsuarioService = $cambiarGradoUsuarioService;
        $this->getPeriodoService = $getPeriodoService;
    }

    public function model(array $rows)
    {
        $grados = Grado::all();
        $grupos = Grupo::all();
        $periodo = $this->getPeriodoService->currentPeriod()->id;

            if($rows["nuip"] == null){
                return null;
            }
            $grado = $rows["grado"];
            $grupo = $rows["grupo"];
            $newGrado = $grados->where("grado", $grado)->first()->id;
            $newGrupo = $grupos->where("grupo", $grupo)->first()->id;
            $nuip = $rows["nuip"];
            $usuario = Usuario::select("id")->where("nuip", $nuip)->first()->id;
            $usuarioGrado = UsuarioGrado::where(
                "usuario_id",
                $usuario,
            )->first();
            $this->cambiarGradoUsuarioService->changeGradeUser($usuario, $usuarioGrado->grado_id, $usuarioGrado->grupo_id, $newGrado, $newGrupo, $periodo);
    }
}
