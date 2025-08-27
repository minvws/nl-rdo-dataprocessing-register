<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\OrganisationUserCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property-read Organisation $organisation
 * @property-read User $user
 */
class OrganisationUser extends Pivot
{
    protected static string $collectionClass = OrganisationUserCollection::class;

    /**
     * @return BelongsTo<Organisation, $this>
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
