<?php

declare(strict_types=1);

namespace App\Contracts\User\Authentication;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as AuthenticatableModel;

interface RegisterInterface
{
    /**
     * Set Guard
     *
     * @param string $guard
     * @return self
     */
    public function setGuard(string $guard): self;

    /**
     * Set fields
     *
     * @param string $field
     * @param string $type
     * @return self
     */
    public function setFillable(string $field, string $type): self;

    /**
     * Set Arguments
     *
     * @param array $arguments
     * @return self
     */
    public function setArguments(array $arguments): self;

    /**
     * Set Model
     *
     * @param string $model
     * @return self
     */
    public function setModel(string $model): self;

    /**
     * Register a new user
     * 
     * @return Authenticatable|null
     */
    public function register(): ?Authenticatable;
}