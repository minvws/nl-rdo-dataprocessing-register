<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\Processor;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasProcessors
{
    public function initializeHasProcessors(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['processors']);
    }

    /**
     * @return MorphToMany<Processor, $this>
     */
    #[RelatedSnapshotSource(Processor::class)]
    public function processors(): MorphToMany
    {
        return $this->morphToMany(Processor::class, 'processor_relatable')->withTimestamps();
    }
}
