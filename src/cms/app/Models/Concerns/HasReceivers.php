<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\Receiver;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasReceivers
{
    public function initializeHasReceivers(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['receivers']);
    }

    /**
     * @return MorphToMany<Receiver, $this>
     */
    #[RelatedSnapshotSource(Receiver::class)]
    public function receivers(): MorphToMany
    {
        return $this->morphToMany(Receiver::class, 'receiver_relatable')->withTimestamps();
    }
}
