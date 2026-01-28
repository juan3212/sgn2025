<?php

namespace App\Http\Controllers\administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class SistemaController extends Controller
{
    //
    public function crearNuevoCiclo(Request $request)
    {
        // 1. Validación de seguridad extra (aunque ya tengas middleware)
        if (!auth()->user()->hasRole('Super-Admin')) {
            abort(403, 'Acceso denegado. Solo Super-Admin.');
        }

        // 2. Validación de datos
        $request->validate([
            'anio_destino' => 'required|numeric|digits:4|min:2026'
        ]);

        $anio = $request->anio_destino;

        try {
            // Log para auditoría
            Log::warning("El usuario " . auth()->user()->id . " inició la creación del ciclo $anio");

            // 3. Ejecutar el comando Artisan
            // Usamos call() que es síncrono (espera a que termine)
            Artisan::call('app:crear-nuevo-ciclo', ['year' => $anio]);
            
            // Capturar la salida del comando para mostrarla al usuario
            $output = Artisan::output();

            return redirect()->back()
                ->with('success', "¡Ciclo escolar $anio creado exitosamente!")
                ->with('console_output', nl2br($output));

        } catch (\Exception $e) {
            Log::error("Error creando ciclo $anio: " . $e->getMessage());
            
            return redirect()->back()
                ->with('error', "Ocurrió un error crítico: " . $e->getMessage());
        }
    }
}
