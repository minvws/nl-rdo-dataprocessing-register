<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Collections\ContactPersonCollection;
use App\Models\ContactPerson;
use App\Models\Contracts\Cloneable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read ContactPersonCollection $contactPersons
 */
trait HasContactPersons
{
    final public function initializeHasContactPersons(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['contactPersons']);
        }
    }

    /**
     * @return MorphToMany<ContactPerson, $this>
     */
    #[RelatedSnapshotSource(ContactPerson::class)]
    final public function contactPersons(): MorphToMany
    {
        return $this->morphToMany(ContactPerson::class, 'contact_person_relatable')->withTimestamps();
    }
}
