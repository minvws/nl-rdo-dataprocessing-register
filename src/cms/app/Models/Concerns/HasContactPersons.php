<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\ContactPerson;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasContactPersons
{
    public function initializeHasContactPersons(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['contactPersons']);
    }

    /**
     * @return MorphToMany<ContactPerson, $this>
     */
    #[RelatedSnapshotSource(ContactPerson::class)]
    public function contactPersons(): MorphToMany
    {
        return $this->morphToMany(ContactPerson::class, 'contact_person_relatable')->withTimestamps();
    }
}
