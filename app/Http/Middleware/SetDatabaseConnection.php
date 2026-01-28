<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;


class SetDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Obtener el año deseado (Por defecto el actual + 1 si estamos a fin de año, o manual)
        $defaultYear = date('Y'); // O 2026
        $anio = session()->get('school_year', $defaultYear);
       
       

        // Validar que el año sea numérico para evitar inyecciones raras
        if (!is_numeric($anio) || strlen($anio) != 4) {
            abort(400, 'Año escolar inválido');
        }

        $nombreBaseDatos = 'sgn' . $anio;

        // 2. VERIFICACIÓN DINÁMICA (Aquí está la magia)
        // Consultamos al esquema de MySQL si esa base de datos existe
        // Usamos la conexión por defecto para preguntar esto
        $dbExists = DB::connection('mysql')->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$nombreBaseDatos]);

        if (!empty($dbExists)) {
            // 3. Si existe, conectamos
            Config::set('database.connections.mysql.database', $nombreBaseDatos);
            DB::purge('mysql');
            DB::reconnect('mysql');
        } else {
            // Si intentan entrar a un año que no existe (ej: 2028), forzar regreso al año actual
            // O mostrar error si ni siquiera la actual existe
            if ($anio != $defaultYear) {
               Session::put('school_year', $defaultYear);
               return redirect()->route('dashboard')->with('error', "El ciclo $anio no está creado todavía.");
            }
        }

        return $next($request);
    }
}
