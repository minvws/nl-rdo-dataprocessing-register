<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factory;
use App\Models\System;

/**
 * @implements Factory<System>
 */
class SystemFactory implements Factory
{
    use DataConverters;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): System
    {
        /** @var System $system */
        $system = System::firstOrNew([
            'description' => $data['Omschrijving'],
            'organisation_id' => $organisationId,
        ]);

        if ($system->exists) {
            return $system;
        }

        $system->organisation_id = $organisationId;
        $system->description = $this->toStringOrNull($data, 'Omschrijving');
        $system->save();

        return $system;
    }
}
