<?php

declare(strict_types=1);

namespace App\Faker;

use App\Enums\YesNoUnknown;
use Faker\Provider\Miscellaneous;
use Webmozart\Assert\Assert;

class ImportProvider extends Miscellaneous
{
    public function importId(): int
    {
        return static::unique()->numberBetween(1, 99_999);
    }

    public function yesNoUnknown(): YesNoUnknown
    {
        $yesNoUnknown = static::randomElement(YesNoUnknown::cases());
        Assert::isInstanceOf($yesNoUnknown, YesNoUnknown::class);

        return $yesNoUnknown;
    }
}
