<?php

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComentarioEstudiantePeriodo;
use Illuminate\Support\Facades\Validator;

class ComentariosController extends Controller
{
    public function saveComentarios(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "comentarios" => "required|array",
            "comentarios.*.estudiante_id" => "required",
            "comentarios.*.comentario" => "required|string|max:500",
            "comentarios.*.periodo_id" => "required",
        ]);

        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => "Error al guardar el comentario",
                "errors" => $validator->errors()
            ]);
        }

        foreach($request->comentarios as $comentario){
            $comentario = ComentarioEstudiantePeriodo::updateOrCreate(
                [
                    "estudiante_id" => $comentario["estudiante_id"],
                    "periodo_id" => $comentario["periodo_id"],
                ],
            [
                "comentario" => $comentario["comentario"],
            ]
        );

        $comentario->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Comentario guardado correctamente"
        ]);
    }
}
