<?php

declare(strict_types=1);

namespace App\Types;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use IPLib\Address\AddressInterface;
use IPLib\Factory;
use IPLib\Range\RangeInterface;
use Webmozart\Assert\Assert;

readonly class IPRanges
{
    public static function make(string $ipRangesString): self
    {
        $ranges = Str::of($ipRangesString)
            ->split('/[\s,]+/')
            ->filter(static function (string $ipRangeString): bool {
                return $ipRangeString !== '';
            })->map(static function (string $ipRangeString): RangeInterface {
                return Factory::parseRangeString($ipRangeString) ?? throw new InvalidArgumentException('Not a valid range');
            });

        return new self($ranges);
    }

    public function contains(string $ipAddress): bool
    {
        $ip = Factory::parseAddressString($ipAddress);
        Assert::isInstanceOf($ip, AddressInterface::class);

        return $this->ipRanges->some(static function (RangeInterface $range) use ($ip): bool {
            return $range->contains($ip);
        });
    }

    /**
     * @param Collection<int, RangeInterface > $ipRanges
     */
    private function __construct(
        private Collection $ipRanges,
    ) {
    }
}
