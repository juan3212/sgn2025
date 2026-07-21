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
        if (
            $user->hasRole([
                "profesor",
                "administrador",
                "Super-Admin",
                "docente",
            ])
        ) {
            return $next($request);
        }

        // 1. Usamos el servicio para ver si DEBE ser expulsado
        if (!$this->paymentService->canAccess($user)) {
            Auth::logout();
            return redirect()
                ->route("home")
                ->with("error", "Regularice sus pagos.");
        }

        if ($request->routeIs("matricula")) {
            if (!$this->paymentService->hasPaidMatricula($user)) {
                // Si no ha pagado matrícula, lo mandamos a otro lado (ej. dashboard o home)
                return redirect()
                    ->route("dashboard-estudiantes")
                    ->with(
                        "error",
                        "Para continuar, es necesario realizar el pago de la matrícula. Tu acceso se habilitará el siguiente día hábil a partir de las 8:00 a.m",
                    );
            }
        }

        // 2. Usamos el servicio para ver si DEBE ver el boletín
        // Nota: Aquí podrías reutilizar el método hasPaid() del servicio si quieres limpiar más.
        $rutaActualEsPermitida =
            $request->routeIs("boletin") ||
            //$request->routeIs("matricula")
            $request->routeIs("dashboard");

        if ($this->paymentService->hasPaid($user) && !$rutaActualEsPermitida) {
            return redirect()->route("dashboard");
        }

        return $next($request);
    }
}
