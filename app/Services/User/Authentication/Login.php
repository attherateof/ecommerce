<?php

declare(strict_types=1);

namespace App\Services\User\Authentication;

use App\Contracts\User\Authentication\LoginInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class Login implements LoginInterface
{
    /**
     * @var null|string
     */
    private ?string $guard = null;

    /**
     * @var null|Request $request
     */
    private ?Request $request = null;

    /**
     * @var array
     */
    private array $arguments = [];

    /**
     * Get Guard
     *
     * @return string
     * @throws \Exception
     */
    private function getGuard(): string
    {
        if (!$this->guard) {
            throw new \Exception("Guard can not be null. Please provide a guard first.");
        }

        return $this->guard;
    }

    /**
     * @inheritDoc
     */
    public function setGuard(string $guard): self
    {
        $this->guard = $guard;

        return $this;
    }

    /**
     * Get Request
     *
     * @return Request
     * @throws \Exception
     */
    private function getRequest(): Request
    {
        if (!$this->request) {
            throw new \Exception("Request can not be null. Please provide a Request first.");
        }

        return $this->request;
    }

    /**
     * @inheritDoc
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Get Arguments
     *
     * @return string
     * @throws \Exception
     */
    private function getArguments(): array
    {
        if (!$this->arguments) {
            throw new \Exception("Arguments can not be null. Please provide arguments first.");
        }

        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(): ?Authenticatable
    {
        $this->ensureIsNotRateLimited();
        $guard = $this->getGuard();
        $arguments = $this->getArguments();
        $stateFulGuard = Auth::guard($guard);

        if ($stateFulGuard->user()) {
            throw ValidationException::withMessages([
                'email' => __('You are already loggedin.'),
            ]);
        }
        
        if (!$stateFulGuard->attempt(['email' => $arguments['email'], 'password' => $arguments['password']], (boolean) $arguments['remember'])) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
        RateLimiter::clear($this->throttleKey());

        return $stateFulGuard->user();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        $request = $this->getRequest();
        event(new Lockout($request));
        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    private function throttleKey(): string
    {
        $request = $this->getRequest();
        $arguments = $this->getArguments();
        return Str::lower($arguments['email']) . '|' . $request->ip();
    }

}