<?php

declare(strict_types=1);

namespace Tests\Helpers;

use function config;
use function sprintf;

class ConfigTestHelper
{
    public static function get(string $key): mixed
    {
        self::validateKey($key);

        return config()->get($key);
    }

    public static function set(string $key, mixed $value): void
    {
        self::validateKey($key);

        config()->set($key, $value);
    }

    private static function validateKey(string $key): void
    {
        if (!config()->has($key)) {
            throw new ConfigNotFoundException(
                sprintf('Config option is not known: %s', $key),
            );
        }
    }
}
