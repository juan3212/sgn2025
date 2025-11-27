<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UsuarioEstadoPago;
use Illuminate\Support\Facades\Auth;
use App\Services\PaymentService;

use Symfony\Component\HttpFoundation\Response;

class PaymentStatus
{

    protected $paymentService;

    // Inyección de dependencia vía Constructor
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // 1. Usamos el servicio para ver si DEBE ser expulsado
        if (! $this->paymentService->canAccess($user)) {
            Auth::logout();
            return redirect()->route('home')->with('error', 'Regularice sus pagos.');
        }

        // 2. Usamos el servicio para ver si DEBE ver el boletín (tu lógica original de 'si')
        // Nota: Aquí podrías reutilizar el método hasPaid() del servicio si quieres limpiar más.
        if ($this->paymentService->hasPaid($user) && !$request->routeIs('boletin')) {
             return redirect()->route('dashboard-estudiantes', $user->id);
        }

        return $next($request);
    }
}
