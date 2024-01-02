<?php

declare(strict_types=1);

namespace App\Contracts\User\Authentication;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

interface LogoutInterface
{
        /**
     * Set Guard
     *
     * @param string $guard
     * @return self
     */
    public function setGuard(string $guard): self;

    /**
     * Set Request
     *
     * @param Request $request
     * @return self
     */
    public function setRequest(Request $request): self;

    /**
     * Logout user
     * 
     * @return Authenticatable|null
     */
    public function logout(): ?Authenticatable;
}