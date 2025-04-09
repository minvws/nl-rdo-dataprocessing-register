<?php

declare(strict_types=1);

namespace App\Rules;

use App\Types\IPRanges as IPRangesType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

use function __;

class IPRanges implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            Assert::string($value);

            IPRangesType::make($value);
        } catch (InvalidArgumentException) {
            $fail(__('validation.custom.ip_range_invalid'));
        }
    }
}
