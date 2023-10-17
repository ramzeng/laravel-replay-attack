<?php

namespace Ramzeng\LaravelReplayAttack\Middlewares;

use Closure;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\Request;
use Ramzeng\LaravelReplayAttack\Exceptions\InvalidNonceException;
use Ramzeng\LaravelReplayAttack\Exceptions\InvalidTimestampException;
use Ramzeng\LaravelReplayAttack\Exceptions\ReplayAttackException;
use Symfony\Component\HttpFoundation\Response;

class ReplayAttack
{
    public function __construct(protected ConfigRepository $configRepository, protected CacheRepository $cacheRepository)
    {
    }

    /**
     * @throws ReplayAttackException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->configRepository->get('replay_attack.enabled')) {
            return $next($request);
        }

        if (! $this->validateTimestamp($request)) {
            throw new InvalidTimestampException();
        }

        if (! $this->validateNonce($request)) {
            throw new InvalidNonceException();
        }

        return $next($request);
    }

    protected function validateTimestamp(Request $request): bool
    {
        if (empty($request->query('timestamp'))) {
            return false;
        }

        $interval = time() - intval($request->query('timestamp'));

        return $interval >= 0 && $interval <= $this->configRepository->get('replay_attack.interval');
    }

    protected function validateNonce(Request $request): bool
    {
        if (empty($request->query('nonce'))) {
            return false;
        }

        $nonceKey = $this->getNonceKey($request, $request->query('nonce'));

        if ($this->cacheRepository->has($nonceKey)) {
            return false;
        }

        $ttl = $this->configRepository->get('replay_attack.interval') - (time() - intval($request->query('timestamp')));

        $this->cacheRepository->put($nonceKey, true, $ttl);

        return true;
    }

    protected function getNonceKey(Request $request, string $nonce): string
    {
        return sprintf('%s:%s:%s', 'replay_attack', sha1($request->host()), $nonce);
    }
}
