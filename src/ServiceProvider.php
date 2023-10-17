<?php

namespace Ramzeng\LaravelReplayAttack;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Ramzeng\LaravelReplayAttack\Middlewares\ReplayAttack;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->app['router']->aliasMiddleware('replay-attack', ReplayAttack::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../configs/replay_attack.php' => config_path('replay_attack.php'),
        ]);
    }
}
