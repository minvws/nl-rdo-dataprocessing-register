<?php

declare(strict_types=1);

namespace MinVWS\Logging\Laravel\Events\Logging;

class UserLoginLogEvent extends GeneralLogEvent
{
    public const EVENT_CODE = '091111';
    public const EVENT_KEY = 'user_login';
}
