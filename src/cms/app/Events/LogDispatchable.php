<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

use function func_get_args;

trait LogDispatchable
{
    use Dispatchable {
        Dispatchable::dispatch as dispatchableDispatch;
    }

    public static function dispatch(): mixed
    {
        Log::debug('event dispatched', ['eventClass' => static::class]);

        return static::dispatchableDispatch(...func_get_args());
    }
}
