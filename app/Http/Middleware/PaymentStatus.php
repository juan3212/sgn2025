<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UsuarioEstadoPago;
use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpFoundation\Response;

class PaymentStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
                // Si el usuario no está logueado, no hagas nada y déjalo pasar
        // (otros middlewares como 'auth' se encargarán de él).
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Solo aplica esta lógica para el rol 'estudiante'
        if ($user->hasRole('estudiante')) {
            $paymentStatus = UsuarioEstadoPago::where('usuario_id', $user->id)->first();

        
            // Si SÍ ha pagado ('si') y NO está ya en la ruta 'boletin', redirígelo.
            if ($paymentStatus->estado_pago == 'si') {
                return redirect()->route('boletin', $user->id); // Pasa el ID correctamente
            }
            // Si NO ha pagado ('no') y NO está ya en la ruta 'home', redirígelo.
            else if ($paymentStatus->estado_pago == 'no') {
                Auth::logout();
                return redirect()->route('home');
            }
        }
        
        // Si ninguna de las condiciones de redirección se cumplió,
        // significa que el usuario ya está donde debería estar o no es un estudiante.
        // ¡Déjalo pasar!
        return $next($request);
    }
}
