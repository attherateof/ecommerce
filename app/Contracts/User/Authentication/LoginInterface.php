<?php

declare(strict_types=1);

namespace App\Contracts\User\Authentication;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

interface LoginInterface
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
     * Set Arguments
     *
     * @param array $arguments
     * @return self
     */
    public function setArguments(array $arguments): self;

    /**
     * Login user
     *
     * @return Authenticatable|null
     */
    public function authenticate(): ?Authenticatable;
}