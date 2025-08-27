<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\Processor;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, Processor>
 */
class ProcessorCollection extends Collection
{
}
