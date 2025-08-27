<?php

declare(strict_types=1);

namespace App\Import\Factories\General;

use App\Components\Uuid\UuidInterface;
use App\Models\LookupListModel;

class LookupListFactory
{
    /**
     * @param class-string<LookupListModel> $model
     */
    public function create(string $model, UuidInterface $organisationId, ?string $value, string $field = 'name'): ?LookupListModel
    {
        if ($value === null) {
            return null;
        }

        /** @var LookupListModel $model */
        $model = $model::firstOrNew([
            $field => $value,
            'organisation_id' => $organisationId,
        ]);

        if ($model->exists) {
            return $model;
        }

        $model->organisation_id = $organisationId;
        $model->enabled = true;
        $model->save();

        return $model;
    }
}
