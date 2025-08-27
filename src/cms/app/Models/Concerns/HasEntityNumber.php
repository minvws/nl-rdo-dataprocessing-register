<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\EntityNumber;
use App\Observers\EntityNumerableObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webmozart\Assert\Assert;

/**
 * @property ?UuidInterface $entity_number_id
 * @property string $number
 *
 * @property-read ?EntityNumber $entityNumber
 */
trait HasEntityNumber
{
    final public static function bootHasEntityNumber(): void
    {
        static::observe(EntityNumerableObserver::class);
    }

    final public function initializeHasEntityNumber(): void
    {
        $this->mergeCasts(['entity_number_id' => UuidCast::class]);
        $this->mergeFillable(['entity_number_id']);
    }

    /**
     * @return BelongsTo<EntityNumber, $this>
     */
    final public function entityNumber(): BelongsTo
    {
        return $this->belongsTo(EntityNumber::class);
    }

    final public function getNumber(): string
    {
        $entityNumber = $this->entityNumber;
        Assert::isInstanceOf($entityNumber, EntityNumber::class);

        return $entityNumber->number;
    }

    final public function getEntityNumberId(): ?UuidInterface
    {
        $entityNumberId = $this->getAttribute('entity_number_id');
        Assert::nullOrIsInstanceOf($entityNumberId, UuidInterface::class);

        return $entityNumberId;
    }

    final public function setEntityNumberId(UuidInterface $id): void
    {
        $this->setAttribute('entity_number_id', $id);
    }
}
