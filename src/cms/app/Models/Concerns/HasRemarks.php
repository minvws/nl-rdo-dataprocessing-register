<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Remark;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Webmozart\Assert\Assert;

/**
 * @property-read Collection<int, Remark> $remarks
 */
trait HasRemarks
{
    public function initializeHasRemarks(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['remarks']);
    }

    /**
     * @return MorphMany<Remark, $this>
     */
    public function remarks(): MorphMany
    {
        return $this->morphMany(Remark::class, 'remark_relatable');
    }
}
