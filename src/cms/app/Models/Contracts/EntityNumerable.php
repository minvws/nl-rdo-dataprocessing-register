<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\Components\Uuid\UuidInterface;
use App\Models\EntityNumber;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface EntityNumerable
{
    /**
     * @return BelongsTo<EntityNumber, Model&$this>
     */
    public function entityNumber(): BelongsTo;

    public function getNumber(): string;

    public function getOrganisation(): Organisation;

    public function getEntityNumberId(): ?UuidInterface;

    public function setEntityNumberId(UuidInterface $id): void;
}
