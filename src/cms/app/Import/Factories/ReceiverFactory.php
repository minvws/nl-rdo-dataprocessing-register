<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\Uuid;
use App\Import\Factory;
use App\Models\Receiver;
use Illuminate\Database\Eloquent\Model;

class ReceiverFactory extends AbstractFactory implements Factory
{
    public function create(array $data, string $organisationId): ?Model
    {
        $receiver = Receiver::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($receiver->exists) {
            return $receiver;
        }

        $receiver->id = Uuid::generate()->toString();
        $receiver->organisation_id = $organisationId;

        $receiver->description = $data['Omschrijving'];

        $receiver->save();

        return $receiver;
    }
}
