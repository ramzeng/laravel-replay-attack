<?php

namespace Ramzeng\LaravelReplayAttack\Exceptions;

class InvalidTimestampException extends ReplayAttackException
{
    public function __construct()
    {
        parent::__construct('Invalid timestamp.');
    }
}
