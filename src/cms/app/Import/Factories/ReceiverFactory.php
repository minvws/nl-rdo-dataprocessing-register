<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factory;
use App\Models\Receiver;

/**
 * @implements Factory<Receiver>
 */
class ReceiverFactory implements Factory
{
    use DataConverters;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): Receiver
    {
        /** @var Receiver $receiver */
        $receiver = Receiver::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($receiver->exists) {
            return $receiver;
        }

        $receiver->organisation_id = $organisationId;
        $receiver->description = $this->toStringOrNull($data, 'Omschrijving');
        $receiver->save();

        return $receiver;
    }
}
