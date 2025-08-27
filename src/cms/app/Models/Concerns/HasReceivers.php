<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\Contracts\Cloneable;
use App\Models\Receiver;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasReceivers
{
    final public function initializeHasReceivers(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['receivers']);
        }
    }

    /**
     * @return MorphToMany<Receiver, $this>
     */
    #[RelatedSnapshotSource(Receiver::class)]
    final public function receivers(): MorphToMany
    {
        return $this->morphToMany(Receiver::class, 'receiver_relatable')->withTimestamps();
    }
}
