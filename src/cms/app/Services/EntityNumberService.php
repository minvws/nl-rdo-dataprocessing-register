<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EntityNumberType;
use App\Models\Contracts\EntityNumerable;
use App\Models\EntityNumber;
use App\Models\EntityNumberCounter;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

use function sprintf;
use function str_pad;

use const STR_PAD_LEFT;

class EntityNumberService
{
    /**
     * @param array<class-string<Model>, EntityNumberType> $entityNumberModelTypes
     */
    public function __construct(
        private readonly array $entityNumberModelTypes,
        private readonly EntityNumberCounterService $entityNumberCounterService,
    ) {
    }

    /**
     * @param class-string<EntityNumerable> $model
     */
    public function generate(Organisation $organisation, string $model): EntityNumber
    {
        Assert::keyExists($this->entityNumberModelTypes, $model);
        $type = $this->entityNumberModelTypes[$model];

        $entityNumberCounter = $this->entityNumberCounterService->getByType($organisation, $type);
        $nextModelNumber = $this->getNextModelNumber($entityNumberCounter);

        return EntityNumber::query()->create([
            'type' => $type,
            'number' => sprintf('%s%s', $entityNumberCounter->prefix, str_pad((string) $nextModelNumber, 4, '0', STR_PAD_LEFT)),
        ]);
    }

    private function getNextModelNumber(EntityNumberCounter $entityNumberCounter): int
    {
        $nextModelNumber = $entityNumberCounter->number;
        $entityNumberCounter::lockForUpdate();

        $entityNumberCounter->number = $nextModelNumber + 1;
        $entityNumberCounter->save();

        return $nextModelNumber;
    }
}
