<?php

declare(strict_types=1);

namespace App\Config;

use Illuminate\Support\Facades\App;

class Environment
{
    public static function isDevelopment(): bool
    {
        return self::isEnvironment(['dev', 'development', 'local']);
    }

    public static function isProduction(): bool
    {
        return self::isEnvironment(['production']);
    }

    public static function isTesting(): bool
    {
        return self::isEnvironment(['test', 'testing']);
    }

    /**
     * @param array<array-key, string> $environmentNames
     */
    private static function isEnvironment(array $environmentNames): bool
    {
        return App::environment($environmentNames);
    }
}
