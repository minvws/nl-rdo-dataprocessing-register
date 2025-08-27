<?php

declare(strict_types=1);

namespace App\Models\Casts;

use App\ValueObjects\Markdown;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

/**
 * @implements CastsAttributes<Markdown, Markdown>
 */
class MarkdownCast implements CastsAttributes
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Markdown
    {
        if ($value === null) {
            return null;
        }

        Assert::string($value);

        return Markdown::fromString($value);
    }

    /**
     * @param array<string, mixed> $attributes
     * @param Markdown|string|null $value
     *
     * @return array<string, string|null>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [$key => null];
        }

        if ($value instanceof Markdown) {
            $value = $value->toString();
        }

        return [$key => $value];
    }
}
