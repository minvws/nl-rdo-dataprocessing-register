<?php

declare(strict_types=1);

namespace App\Import;

use App\Components\Uuid\UuidInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
interface Factory
{
    /**
     * @param array<string, mixed> $data
     *
     * @return TModel
     */
    public function create(array $data, UuidInterface $organisationId): ?Model;
}
