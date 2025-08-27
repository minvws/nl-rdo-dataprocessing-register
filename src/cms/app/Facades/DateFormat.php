<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\DateFormatService;
use App\ValueObjects\CalendarDate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Facade;

/**
 * @see DateFormatService
 *
 * @method static string toFilename(CalendarDate|CarbonImmutable $date)
 * @method static string toDate(CalendarDate|CarbonImmutable|null $date)
 * @method static string toDateTime(CalendarDate|CarbonImmutable|null $date)
 * @method static string forValidUntilShort(CalendarDate|CarbonImmutable $date)
 */
class DateFormat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DateFormatService::class;
    }
}
