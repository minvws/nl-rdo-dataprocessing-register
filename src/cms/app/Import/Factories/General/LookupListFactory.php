<?php

declare(strict_types=1);

namespace App\Import\Factories\General;

use App\Components\Uuid\Uuid;
use App\Models\LookupListModel;

class LookupListFactory
{
    /**
     * @param class-string<LookupListModel> $model
     */
    public function create(string $model, string $organisationId, ?string $value, string $field = 'name'): ?LookupListModel
    {
        if ($value === null) {
            return null;
        }

        $model = $model::firstOrNew([
            $field => $value,
            'organisation_id' => $organisationId,
        ]);

        if ($model->exists) {
            return $model;
        }

        $model->id = Uuid::generate()->toString();
        $model->organisation_id = $organisationId;
        $model->enabled = true;

        $model->save();

        return $model;
    }
}
