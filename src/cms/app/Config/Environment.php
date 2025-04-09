<?php

declare(strict_types=1);

namespace App\Config;

use Illuminate\Support\Facades\App;
use RuntimeException;

use function is_bool;

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
     * @param array<int, string> $environmentNames
     */
    private static function isEnvironment(array $environmentNames): bool
    {
        $environment = App::environment($environmentNames);

        if (!is_bool($environment)) {
            throw new RuntimeException('Unable to determine environment');
        }

        return $environment;
    }
}
