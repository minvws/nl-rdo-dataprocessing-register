<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\OrganisationUser;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, OrganisationUser>
 */
class OrganisationUserCollection extends Collection
{
}
