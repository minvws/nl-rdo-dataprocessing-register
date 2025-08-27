<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Config;
use App\ValueObjects\CalendarDate;
use Carbon\CarbonImmutable;

class DateFormatService
{
    public const FORMAT_FILENAME = 'Y-m-d-H.i';
    public const FORMAT_DATE = 'd-m-Y';
    public const FORMAT_DATE_TIME = 'd-m-Y H:i';
    public const FORMAT_VALID_UNTIL_SHORT = 'H:i d/m/Y';
    public const FORMAT_DATE_TIME_INPUT = 'Y-m-d H:i:s';

    // https://momentjs.com/docs/#/parsing/string-format/
    public const FORMAT_ISO_SENTENCE = 'D MMMM YYYY HH:mm';

    public static function toFilename(CalendarDate|CarbonImmutable $date): string
    {
        return self::format($date, self::FORMAT_FILENAME);
    }

    public static function toDate(CalendarDate|CarbonImmutable|null $date): ?string
    {
        if ($date === null) {
            return null;
        }

        return self::format($date, self::FORMAT_DATE);
    }

    public static function toDateTime(CalendarDate|CarbonImmutable|null $date): ?string
    {
        if ($date === null) {
            return null;
        }

        return self::format($date, self::FORMAT_DATE_TIME);
    }

    public static function forValidUntilShort(CalendarDate|CarbonImmutable $date): string
    {
        return self::format($date, self::FORMAT_VALID_UNTIL_SHORT);
    }

    public static function toSentence(CalendarDate|CarbonImmutable|null $date): ?string
    {
        if ($date === null) {
            return null;
        }

        return self::formatIso($date, self::FORMAT_ISO_SENTENCE);
    }

    public static function getDisplayTimezone(): string
    {
        return Config::string('app.display_timezone');
    }

    private static function format(CalendarDate|CarbonImmutable $date, string $format): string
    {
        if ($date instanceof CarbonImmutable) {
            $date = $date->setTimezone(self::getDisplayTimezone());
        }

        return $date->format($format);
    }

    private static function formatIso(CalendarDate|CarbonImmutable $date, string $format): string
    {
        if ($date instanceof CarbonImmutable) {
            $date = $date->setTimezone(self::getDisplayTimezone());
        }

        return $date->isoFormat($format);
    }
}
