<?php

declare(strict_types=1);

namespace App\Faker;

use DateTime;
use Faker\Provider\DateTime as FakerDateTime;

class DateTimeProvider extends FakerDateTime
{
    public function anyDate(): DateTime
    {
        return static::dateTimeBetween(endDate: '+30 years');
    }
}
