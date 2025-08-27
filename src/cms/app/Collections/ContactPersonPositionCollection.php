<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\ContactPerson;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, ContactPerson>
 */
class ContactPersonPositionCollection extends Collection
{
}
