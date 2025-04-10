<?php

declare(strict_types=1);

namespace App\Models\Casts;

use App\Components\Uuid\Uuid;
use App\Components\Uuid\UuidInterface;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

/**
 * @implements CastsAttributes<UuidInterface, UuidInterface>
 */
class UuidCast implements CastsAttributes
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?UuidInterface
    {
        if ($value === null) {
            return null;
        }

        Assert::string($value);

        return Uuid::fromString($value);
    }

    /**
     * @param array<string, mixed> $attributes
     *
     * @return array<string, string|null>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        Assert::isInstanceOf($value, UuidInterface::class);

        return [$key => $value->toString()];
    }
}
