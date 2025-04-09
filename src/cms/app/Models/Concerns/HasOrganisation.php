<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $organisation_id
 * @property Organisation $organisation
 */
trait HasOrganisation
{
    public function initializeHasOrganisation(): void
    {
        $this->mergeCasts(['organisation_id' => 'string']);
        $this->mergeFillable(['organisation_id']);
    }

    /**
     * @return BelongsTo<Organisation, $this>
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function getOrganisation(): Organisation
    {
        return $this->organisation;
    }
}
