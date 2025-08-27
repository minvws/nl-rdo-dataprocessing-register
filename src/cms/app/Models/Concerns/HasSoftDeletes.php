<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property ?CarbonImmutable $deleted_at
 */
trait HasSoftDeletes
{
    use SoftDeletes;
}
