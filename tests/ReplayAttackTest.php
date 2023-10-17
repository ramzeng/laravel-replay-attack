<?php

namespace Ramzeng\LaravelReplayAttack\Tests;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use Ramzeng\LaravelReplayAttack\Exceptions\InvalidNonceException;
use Ramzeng\LaravelReplayAttack\Exceptions\InvalidTimestampException;
use Ramzeng\LaravelReplayAttack\Middlewares\ReplayAttack;
use Throwable;

class ReplayAttackTest extends TestCase
{
    public function test_replay_attack()
    {
        $configRepository = new ConfigRepository([
            'replay_attack' => [
                'enabled' => true,
                'interval' => 60,
            ],
        ]);

        $cacheRepository = new CacheRepository(new ArrayStore());

        $middleware = new ReplayAttack($configRepository, $cacheRepository);

        $nonce = Str::uuid()->toString();

        // ok
        $request = Request::create('http://localhost', 'GET', [
            'timestamp' => time(),
            'nonce' => $nonce,
        ]);

        $middleware->handle($request, function () {
            return new Response();
        });

        // invalid timestamp
        try {
            $request = Request::create('http://localhost', 'GET', [
                'timestamp' => time() - 61,
                'nonce' => $nonce,
            ]);

            $middleware->handle($request, function () {
                return new Response();
            });
        } catch (Throwable $e) {
            $this->assertInstanceOf(InvalidTimestampException::class, $e);
        }

        // invalid nonce
        try {
            $request = Request::create('http://localhost', 'GET', [
                'timestamp' => time(),
                'nonce' => $nonce,
            ]);

            $middleware->handle($request, function () {
                return new Response();
            });
        } catch (Throwable $e) {
            $this->assertInstanceOf(InvalidNonceException::class, $e);
        }
    }
}
