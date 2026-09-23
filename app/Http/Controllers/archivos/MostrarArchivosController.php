<?php

namespace App\Http\Controllers\archivos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MostrarArchivosController extends Controller
{
    public function mostrar($path, $ver)
    {
        $usuarioActual = Auth::user();
        if (!$usuarioActual) {
            abort(401, "No autenticado");
        }

        // Sanitización básica: evitar path traversal y caracteres nulos
        $cleanPath = str_replace('\\', '/', (string)$path);
        if (str_contains($cleanPath, '..') || str_contains($cleanPath, "\0")) {
            abort(404, "Archivo no encontrado");
        }

        $baseDir = realpath(storage_path("app/private"));
        if (!$baseDir) {
            abort(404, "Archivo no encontrado");
        }

        $targetPath = $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($cleanPath, '/'));
        $realTargetPath = realpath($targetPath);

        // Validar que el archivo exista en disco, sea un archivo regular y esté estrictamente dentro de storage/app/private
        if (!$realTargetPath || !is_file($realTargetPath) || !str_starts_with($realTargetPath, $baseDir)) {
            abort(404, "Archivo no encontrado");
        }

        // Control de autorización estricto
        $isPrivileged = $usuarioActual->hasRole(['administrador', 'profesor', 'Super-Admin']);

        if (!$isPrivileged) {
            $userNuip = (string)$usuarioActual->nuip;
            $allowedNuips = !empty($userNuip) ? [$userNuip] : [];

            // Si es acudiente/padre, permitir también los documentos de sus hijos a cargo
            $childrenNuips = DB::table('usuario_has_child')
                ->join('usuarios', 'usuarios.id', '=', 'usuario_has_child.child_id')
                ->where('usuario_has_child.parent_id', $usuarioActual->id)
                ->pluck('usuarios.nuip')
                ->map(fn($n) => (string)$n)
                ->toArray();

            $allowedNuips = array_merge($allowedNuips, $childrenNuips);

            $pathSegments = explode('/', $cleanPath);
            $hasAccess = false;
            foreach ($allowedNuips as $nuip) {
                if (!empty($nuip) && in_array($nuip, $pathSegments, true)) {
                    $hasAccess = true;
                    break;
                }
            }

            if (!$hasAccess) {
                abort(403, "No tiene permisos para acceder a este archivo");
            }
        }

        return response()->file($realTargetPath);
    }
}
