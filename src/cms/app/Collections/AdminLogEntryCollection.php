<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\AdminLogEntry;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, AdminLogEntry>
 */
class AdminLogEntryCollection extends Collection
{
}
