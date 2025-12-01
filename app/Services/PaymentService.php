<?php

namespace App\Services;

use App\Models\Usuario;
use App\Models\UsuarioEstadoPago;
use App\Models\PagoMatricula;

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

    public function hasPaidMatricula(Usuario $user): bool
    {
        // Si no es estudiante, asumimos que tiene acceso (o false, según tu lógica de negocio)
        if (! $user->hasRole('estudiante')) {
            return true; 
        }

        // Consultamos la nueva tabla 'pago_matricula'
        // Ajusta el nombre del modelo o la tabla según tu base de datos
        $matricula = PagoMatricula::where('user_id', $user->id)
                        ->first();

        // Aquí defines la lógica: ¿Debe existir el registro? ¿Debe tener un estado 'pagado'?
        // Ejemplo: Si existe y el estado es 'pagado' (ajusta según tus columnas reales)
        if ($matricula && $matricula->estado_pago == 'si') {
            return true;
        }

        return false;
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