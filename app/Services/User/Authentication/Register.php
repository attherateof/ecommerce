<?php

declare(strict_types=1);

namespace App\Services\User\Authentication;

use App\Contracts\User\Authentication\RegisterInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as AuthenticatableModel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class Register implements RegisterInterface
{
    /**
     * @var null|string
     */
    private ?string $guard = null;

    /**
     * @var array<string|int, mixed>
     */
    private array $fillable = [];

    /**
     * @var array<string|int, mixed>
     */
    private array $defaultFillable = [
        "name" => "string",
        "email" => "string",
        "password" => "hash"
    ];

    /**
     * @var array<string|int, mixed>
     */
    private array $arguments = [];

    /**
     * @var null|string
     */
    private ?string $model = null;

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
     * Get Guard
     *
     * @return string
     * @throws \Exception
     */
    private function getFillable(): array
    {
        if (!count($this->fillable)) {
            $this->fillable = $this->defaultFillable;
        }

        return $this->fillable;
    }

    /**
     * @inheritDoc
     */
    public function setFillable(string $field, string $type): self
    {
        $this->fillable[] = [$field => $type];

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
     * Get Request
     *
     * @return Request
     * @throws \Exception
     */
    private function getModel(): string
    {
        if (!$this->model) {
            throw new \Exception("Model can not be null. Please provide a model first.");
        }

        return $this->model;
    }

    /**
     * @inheritDoc
     */
    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

     /**
     * @inheritDoc
     */
    public function register(): ?Authenticatable
    {
        $model = $this->getModel();
        $inputs = $this->getFilteredArguments();
        $guard = $this->getGuard();
        /** @var AuthenticatableModel $model **/
        $user = $model::create($inputs);
        event(new Registered($user));
        Auth::guard($guard)->login($user);

        return Auth::guard($guard)->user();
    }

    /**
     * Get filtered arguments
     *
     * @return array<int|string, mixed>
     */
    private function getFilteredArguments(): array
    {
        $arguments = $this->getArguments();
        $fillable = $this->getFillable();
        $inputs = [];
        foreach ($fillable as $fieldName => $type) {
            if (array_key_exists($fieldName, $arguments)) {
                $value = $arguments[$fieldName];
                if ($type === "hash") {
                    $value = Hash::make($arguments[$fieldName]);
                }
                $inputs[$fieldName] = $value;
            }
        }

        return $inputs;
    }
}