<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @template TModel of Model
 * @template TCollection of Collection
 *
 * @property-read ?UuidInterface $parent_id
 *
 * @property-read ?TModel $parent
 * @property-read TCollection $children
 */
trait HasChildren
{
    final public function initializeHasChildren(): void
    {
        $this->mergeCasts(['parent_id' => UuidCast::class]);
        $this->mergeFillable(['parent_id']);
    }

    /**
     * @return BelongsTo<TModel, $this>
     */
    final public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<TModel, $this>
     */
    final public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
