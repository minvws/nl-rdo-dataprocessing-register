<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\UserLoginToken;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, UserLoginToken>
 */
class UserLoginTokenCollection extends Collection
{
}
