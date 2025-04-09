<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EntityNumberType;
use App\Models\EntityNumberCounter;
use App\Models\Organisation;
use Webmozart\Assert\Assert;

use function sprintf;

class EntityNumberCounterService
{
    public function getByType(Organisation $organisation, EntityNumberType $entityNumberType): EntityNumberCounter
    {
        $entityNumberCounterId = sprintf('%s_entity_number_counter_id', $entityNumberType->value);
        $entityNumberId = $organisation->getAttribute($entityNumberCounterId);

        Assert::notNull($entityNumberId);

        return EntityNumberCounter::lockForUpdate()
            ->where('id', $entityNumberId)
            ->firstOrFail();
    }
}
