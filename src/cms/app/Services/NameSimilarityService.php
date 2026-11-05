<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function array_filter;
use function array_values;
use function floor;
use function mb_strtolower;
use function min;
use function preg_replace;
use function preg_split;
use function similar_text;
use function strcmp;
use function strlen;

use const PREG_SPLIT_NO_EMPTY;

class NameSimilarityService
{
    /**
     * Names are reduced to letters and digits only, so "Acme B.V.", "acme bv" and "Acme-BV" are all identical.
     * Legal forms are deliberately kept: "Acme B.V." and "Acme N.V." are different legal entities.
     */
    public static function normalize(string $name): string
    {
        $normalized = mb_strtolower(Str::ascii($name));
        $normalized = preg_replace('/[^a-z0-9]+/', '', $normalized);
        Assert::string($normalized);

        return $normalized;
    }

    /**
     * Returns the words of a name with at least the given number of letters or digits, lowercased and in ascii.
     *
     * @return list<string>
     */
    public static function words(string $name, int $minimumLength): array
    {
        $words = preg_split('/[^a-z0-9]+/', mb_strtolower(Str::ascii($name)), -1, PREG_SPLIT_NO_EMPTY);
        Assert::isList($words);

        return array_values(array_filter(
            $words,
            static fn (string $word): bool => strlen($word) >= $minimumLength,
        ));
    }

    /**
     * Returns the similarity percentage of two normalized names, or null when they are not similar enough.
     */
    public static function compare(string $first, string $second, float $minimumSimilarity): ?float
    {
        if ($first === '' || $second === '') {
            return null;
        }

        $firstLength = strlen($first);
        $secondLength = strlen($second);

        // similar_text can never score higher than this, so skip the expensive comparison altogether
        $upperBound = 200 * min($firstLength, $secondLength) / ($firstLength + $secondLength);
        if ($upperBound < $minimumSimilarity) {
            return null;
        }

        $similarity = self::calculateSimilarity($first, $second);
        if ($similarity < $minimumSimilarity) {
            return null;
        }

        return $similarity;
    }

    private static function calculateSimilarity(string $first, string $second): float
    {
        if ($first === $second) {
            return 100.0;
        }

        // similar_text is not symmetric, a fixed argument order keeps the result independent of the record order
        if (strcmp($first, $second) > 0) {
            [$first, $second] = [$second, $first];
        }

        similar_text($first, $second, $percentage);

        // floor instead of round, so 100.0 is only reported for names that are identical after normalisation
        return floor($percentage * 10) / 10;
    }
}
