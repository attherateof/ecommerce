<?php

declare(strict_types=1);

namespace App\Services\User\Authentication;

use App\Contracts\User\Authentication\LogoutInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Logout implements LogoutInterface
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
    public function logout(): ?Authenticatable
    {
        $guard = $this->getGuard();
        $request = $this->getRequest();

        $auth = Auth::guard($guard);
        $user = $auth->user();
        
        $auth->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $user;
    }
}