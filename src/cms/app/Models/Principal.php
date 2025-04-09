<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Authorization\Role;

readonly class Principal
{
    /**
     * @param array<Role> $roles
     */
    public function __construct(
        public array $roles,
    ) {
    }
}
