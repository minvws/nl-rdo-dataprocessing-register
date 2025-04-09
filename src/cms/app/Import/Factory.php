<?php

declare(strict_types=1);

namespace App\Import;

use Illuminate\Database\Eloquent\Model;

interface Factory
{
    public function create(array $data, string $organisationId): ?Model;
}
