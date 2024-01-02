<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\User\Authentication\LoginInterface;
use App\Services\User\Authentication\Login;
use App\Contracts\User\Authentication\RegisterInterface;
use App\Services\User\Authentication\Register;
use App\Contracts\User\Authentication\LogoutInterface;
use App\Services\User\Authentication\Logout;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginInterface::class, Login::class);
        $this->app->bind(LogoutInterface::class, Logout::class);
        $this->app->bind(RegisterInterface::class, Register::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
