<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasTags
{
    public function initializeHasTags(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['tags']);
    }

    /**
     * @return MorphToMany<Tag, $this>
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
