<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\ContactPersonCollection;
use App\Collections\ContactPersonPositionCollection;
use Database\Factories\ContactPersonPositionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read ContactPersonCollection $contactPersons
 */
class ContactPersonPosition extends LookupListModel
{
    /** @use HasFactory<ContactPersonPositionFactory> */
    use HasFactory;

    protected static string $collectionClass = ContactPersonPositionCollection::class;
    protected $table = 'contact_person_positions';

    /**
     * @return HasMany<ContactPerson, $this>
     */
    public function contactPersons(): HasMany
    {
        return $this->hasMany(ContactPerson::class);
    }
}
