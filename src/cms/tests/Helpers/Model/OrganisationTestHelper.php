<?php

declare(strict_types=1);

namespace Tests\Helpers\Model;

use App\Models\Organisation;

class OrganisationTestHelper
{
    /**
     * @param array<string, mixed> $attributes
     */
    public static function create(array $attributes = []): Organisation
    {
        return Organisation::factory()
            ->create($attributes);
    }
}
