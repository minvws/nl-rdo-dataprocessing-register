<?php

declare(strict_types=1);

namespace App\Faker;

use App\ValueObjects\CalendarDate;
use DateTime;
use Faker\Provider\DateTime as FakerDateTime;

class DateTimeProvider extends FakerDateTime
{
    public function anyDate(): DateTime
    {
        return static::dateTimeBetween(endDate: '+30 years');
    }

    public function calendarDate(): CalendarDate
    {
        return CalendarDate::instance(static::dateTimeBetween(endDate: '+30 years'));
    }
}
