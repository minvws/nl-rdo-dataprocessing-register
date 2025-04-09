<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\Uuid;
use App\Import\Factory;
use App\Models\System;
use Illuminate\Database\Eloquent\Model;

class SystemFactory extends AbstractFactory implements Factory
{
    public function create(array $data, string $organisationId): ?Model
    {
        $system = System::firstOrNew([
            'description' => $data['Omschrijving'],
            'organisation_id' => $organisationId,
        ]);

        if ($system->exists) {
            return $system;
        }

        $system->id = Uuid::generate()->toString();
        $system->organisation_id = $organisationId;

        $system->description = $data['Omschrijving'];

        $system->save();

        return $system;
    }
}
