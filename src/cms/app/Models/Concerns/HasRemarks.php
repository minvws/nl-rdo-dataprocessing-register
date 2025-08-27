<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Collections\RemarkCollection;
use App\Models\Contracts\Cloneable;
use App\Models\Remark;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property-read RemarkCollection $remarks
 */
trait HasRemarks
{
    final public function initializeHasRemarks(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['remarks']);
        }
    }

    /**
     * @return MorphMany<Remark, $this>
     */
    final public function remarks(): MorphMany
    {
        return $this->morphMany(Remark::class, 'remark_relatable');
    }
}
