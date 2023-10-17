<?php

namespace Ramzeng\LaravelReplayAttack\Exceptions;

class InvalidNonceException extends ReplayAttackException
{
    public function __construct()
    {
        parent::__construct('Invalid nonce.');
    }
}
