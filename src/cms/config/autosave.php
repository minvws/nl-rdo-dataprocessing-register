<?php

declare(strict_types=1);

return [
    'enabled' => (bool) env('AUTOSAVE_ENABLED', true),
    'poll_interval_seconds' => 5,
    'retention_days' => 7,
];
