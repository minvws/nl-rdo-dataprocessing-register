<?php

declare(strict_types=1);

namespace App\Collections;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends Collection<array-key, Document>
 */
class DocumentCollection extends Collection
{
}
