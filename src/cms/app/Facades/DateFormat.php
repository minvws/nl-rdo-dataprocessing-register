<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\DateFormatService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Facade;

/**
 * @see DateFormatService
 *
 * @method static string toFilename(CarbonImmutable $date)
 * @method static string toDate(?CarbonImmutable $date)
 * @method static string toDateTime(?CarbonImmutable $date)
 */
class DateFormat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DateFormatService::class;
    }
}
