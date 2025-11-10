<?php

namespace App\Http\Controllers\archivos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MostrarArchivosController extends Controller
{
    //
      public function mostrar($path)
    {
        $ruta = storage_path("app/private/".$path);
        
        if(file_exists($ruta)){
            return response()->file($ruta);
        }else{
            abort(404, "Archivo no encontrado");
        }
    }
}
