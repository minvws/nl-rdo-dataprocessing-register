<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\EntityNumber;
use App\Observers\EntityNumerableObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webmozart\Assert\Assert;

/**
 * @property ?string $entity_number_id
 * @property string $number
 *
 * @property-read ?EntityNumber $entityNumber
 */
trait HasEntityNumber
{
    public static function bootHasEntityNumber(): void
    {
        static::observe(EntityNumerableObserver::class);
    }

    public function initializeHasEntityNumber(): void
    {
        $this->mergeFillable(['entity_number_id']);
    }

    /**
     * @return BelongsTo<EntityNumber, $this>
     */
    public function entityNumber(): BelongsTo
    {
        return $this->belongsTo(EntityNumber::class);
    }

    public function getNumber(): string
    {
        $entityNumber = $this->entityNumber;
        Assert::isInstanceOf($entityNumber, EntityNumber::class);

        return $entityNumber->number;
    }

    public function getEntityNumberId(): ?string
    {
        $entityNumberId = $this->getAttribute('entity_number_id');
        Assert::nullOrString($entityNumberId);

        return $entityNumberId;
    }

    public function setEntityNumberId(string $id): void
    {
        $this->setAttribute('entity_number_id', $id);
    }
}
