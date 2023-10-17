<?php

return [
    /**
     * The status of the replay attack.
     */
    'enabled' => env('REPLAY_ATTACK_ENABLED', true),

    /**
     * The interval between the request and the server.
     */
    'interval' => env('REPLAY_ATTACK_INTERVAL', 60),
];
