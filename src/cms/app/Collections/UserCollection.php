<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, User>
 */
class UserCollection extends Collection
{
}
