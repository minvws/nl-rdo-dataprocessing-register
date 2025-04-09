<?php

declare(strict_types=1);

namespace App\Services\SqlExport;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function count;
use function explode;
use function floor;
use function sprintf;
use function strlen;

class IndexNameTruncater
{
    private const INDEX_NAME_MAX_LENGTH = 64;

    public static function foreignKey(string ...$parts): string
    {
        return self::truncateIndexName($parts, '_foreign');
    }

    public static function index(string ...$parts): string
    {
        return self::truncateIndexName($parts, '_index');
    }

    public static function unique(string ...$parts): string
    {
        return self::truncateIndexName($parts, '_unique');
    }

    /**
     * @param array<string> $parts
     */
    private static function truncateIndexName(array $parts, string $suffix): string
    {
        $truncated = Arr::join(self::truncateParts($parts, self::INDEX_NAME_MAX_LENGTH - strlen($suffix)), '_');

        return sprintf('%s%s', $truncated, $suffix);
    }

    /**
     * @param array<string> $parts
     *
     * @return array<string>
     */
    private static function truncateParts(array $parts, int $indexNameLimit): array
    {
        $maxLengthPerPart = (int) floor(($indexNameLimit - (count($parts) - 1)) / count($parts));

        $truncatedParts = Arr::map($parts, static function (string $part) use ($maxLengthPerPart): string {
            if (!Str::contains($part, '_')) {
                return Str::substr($part, 0, $maxLengthPerPart);
            }

            return Arr::join(self::truncateParts(explode('_', $part), $maxLengthPerPart), '_');
        });
        Assert::allString($truncatedParts);

        return $truncatedParts;
    }
}
