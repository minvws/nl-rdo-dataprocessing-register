<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\Models\Organisation;

interface TenantAware
{
    public function getOrganisation(): Organisation;
}
