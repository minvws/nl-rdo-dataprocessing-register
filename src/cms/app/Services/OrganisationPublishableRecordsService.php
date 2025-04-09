<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contracts\Publishable;
use App\Models\Organisation;
use App\Models\States\Snapshot\Established;
use Illuminate\Support\Collection;

class OrganisationPublishableRecordsService
{
    /**
     * @return Collection<int, Publishable>
     */
    public function getPublishableRecords(Organisation $organisation): Collection
    {
        /** @var Collection<int, Publishable> $publishables */
        $publishables = new Collection();

        // add all publishable record-types
        $publishables = $publishables->merge($organisation->avgResponsibleProcessingRecords);

        // filter non-publishable records
        return $publishables->filter(static function (Publishable $publishable): bool {
            if (!$publishable->canBePublished()) {
                return false;
            }

            $snapshot = $publishable->getLatestSnapshotWithState([Established::class]);

            if ($snapshot === null) {
                return false;
            }

            if ($snapshot->snapshotData === null) {
                return false;
            }

            return $snapshot->snapshotData->public_markdown !== null;
        });
    }
}
