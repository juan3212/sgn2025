<?php

namespace App\Services;

use App\Models\Usuario;
use App\Models\UsuarioEstadoPago;

class PaymentService
{
    public function __construct()
    {
        //
    }

    public function canAccess(Usuario $user): bool
    {
        // Si no es estudiante, la lógica de pagos no aplica, así que puede pasar.
        if (! $user->hasRole('estudiante')) {
            return true;
        }

        $paymentStatus = UsuarioEstadoPago::where('usuario_id', $user->id)->first();

        // Si no tiene registro o el estado es 'no', bloqueamos el acceso.
        if ($paymentStatus && $paymentStatus->estado_pago == 'no') {
            return false;
        }

        return true;
    }
    
    /**
     * Opcional: Un método específico para saber si ya pagó (útil para redirecciones a boletín)
     */
    public function hasPaid(Usuario $user): bool
    {
        if (! $user->hasRole('estudiante')) return false;
        
        $status = UsuarioEstadoPago::where('usuario_id', $user->id)->first();
        return $status && $status->estado_pago == 'si';
    }
}