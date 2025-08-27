<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\Contracts\Cloneable;
use App\Models\Processor;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasProcessors
{
    final public function initializeHasProcessors(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['processors']);
        }
    }

    /**
     * @return MorphToMany<Processor, $this>
     */
    #[RelatedSnapshotSource(Processor::class)]
    final public function processors(): MorphToMany
    {
        return $this->morphToMany(Processor::class, 'processor_relatable')->withTimestamps();
    }
}
