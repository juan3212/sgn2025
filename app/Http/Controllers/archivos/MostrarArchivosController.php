<?php

namespace App\Http\Controllers\archivos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class MostrarArchivosController extends Controller
{
    //
      public function mostrar($path, $ver)
    {

        $usuarioActual = Auth::user();
        if(!str_contains($path, $usuarioActual->nuip) && !$usuarioActual->hasRole(['administrador', 'profesor', 'Super-Admin'])){
            abort(404, "Archivo no encontrado");
        }

        $ruta = storage_path("app/private/".$path);


        
        if(file_exists($ruta)){
            return response()->file($ruta);
        }else{
            abort(404, "Archivo no encontrado");
        }
    }
}
