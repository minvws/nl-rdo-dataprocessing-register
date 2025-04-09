<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $id
 */
trait IsSortable
{
    protected static string $defaultOrderByColumn = 'sort';

    protected static function bootIsSortable(): void
    {
        $defaultOrderByColumn = self::$defaultOrderByColumn;

        static::addGlobalScope('order', static function (Builder $builder) use ($defaultOrderByColumn): void {
            $builder->orderBy($defaultOrderByColumn);
        });
    }
}
