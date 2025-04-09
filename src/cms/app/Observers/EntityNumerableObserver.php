<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Contracts\EntityNumerable;
use App\Services\EntityNumberService;

readonly class EntityNumerableObserver
{
    public function __construct(
        private EntityNumberService $entityNumberService,
    ) {
    }

    public function creating(EntityNumerable $entityNumerable): void
    {
        if ($entityNumerable->getEntityNumberId() !== null) {
            return;
        }

        $entityNumber = $this->entityNumberService->generate($entityNumerable->getOrganisation(), $entityNumerable::class);

        $entityNumerable->setEntityNumberId($entityNumber->id);
    }
}
