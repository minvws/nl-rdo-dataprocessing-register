<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, Tag>
 */
class TagCollection extends Collection
{
}
