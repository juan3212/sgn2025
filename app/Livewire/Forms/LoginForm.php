<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Usuario;
use App\Services\PaymentService;

class LoginForm extends Form
{

    #[Validate('required|string')]
    public string $nuip = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(PaymentService $paymentService): void
    {
        $this->ensureIsNotRateLimited();

        $usuario = Usuario::where('nuip', $this->nuip)
        ->with('roles')
        ->with('bloqueos')
        ->first();

        if ($usuario->bloqueos()->exists()) {
            throw ValidationException::withMessages([
                'form.nuip' => 'El usuario está bloqueado, por favor pongase en contacto con el colegio.',
            ]);
        }


        if (!$usuario || ! Auth::attempt($this->only(['nuip', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.nuip' => trans('auth.failed'),
            ]);
        }
        
        if (! $paymentService->canAccess(Auth::user())) {
            
            Auth::logout(); // Cerramos sesión inmediatamente

            throw ValidationException::withMessages([
                'form.nuip' => 'Debe estar a paz y salvo para poder acceder al sistema.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        Auth::login($usuario, $this->remember);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.nuip' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->nuip).'|'.request()->ip());
    }
}
