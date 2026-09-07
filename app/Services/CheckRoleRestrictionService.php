<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CheckRoleRestrictionService
{
    /**
     * Comprueba si un rol específico tiene el acceso bloqueado.
     *
     * @param int|string $roleId
     * @return bool
     */
    public function isRoleBlocked($roleId): bool
    {
        if (empty($roleId)) {
            return false;
        }

        // Consulta sobre la tabla que definiste
        return DB::table('restriccion_acceso_roles')
            ->where('role_id', $roleId)
            ->where('bloqueado', true)
            ->exists();
    }

    /**
     * Opcional: Obtener la descripción o mensaje del bloqueo.
     *
     * @param int|string $roleId
     * @return string|null
     */
}
