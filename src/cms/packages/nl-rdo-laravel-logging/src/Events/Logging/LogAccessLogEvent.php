<?php

declare(strict_types=1);

namespace MinVWS\Logging\Laravel\Events\Logging;

class LogAccessLogEvent extends GeneralLogEvent
{
    public const EVENT_CODE = '080002';
    public const EVENT_KEY = 'log_access';
}
