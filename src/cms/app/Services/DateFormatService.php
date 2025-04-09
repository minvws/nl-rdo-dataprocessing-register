<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Config;
use Carbon\CarbonImmutable;

class DateFormatService
{
    public const FORMAT_FILENAME = 'Y-m-d-H.i';
    public const FORMAT_DATE = 'd-m-Y';
    public const FORMAT_DATE_TIME = 'd-m-Y H:i';
    public const FORMAT_DATE_TIME_INPUT = 'Y-m-d H:i:s';

    public static function toFilename(CarbonImmutable $date): string
    {
        return self::format($date, self::FORMAT_FILENAME);
    }

    public static function toDate(?CarbonImmutable $date): ?string
    {
        if ($date === null) {
            return null;
        }

        return self::format($date, self::FORMAT_DATE);
    }

    public static function toDateTime(?CarbonImmutable $date): ?string
    {
        if ($date === null) {
            return null;
        }

        return self::format($date, self::FORMAT_DATE_TIME);
    }

    public static function format(CarbonImmutable $date, string $format): string
    {
        return $date
            ->setTimezone(self::getDisplayTimezone())
            ->format($format);
    }

    public static function getDisplayTimezone(): string
    {
        return Config::string('app.display_timezone');
    }
}
