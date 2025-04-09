<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\Uuid;
use App\Import\Factory;
use App\Models\Responsible;
use Illuminate\Database\Eloquent\Model;

class ResponsibleFactory extends AbstractFactory implements Factory
{
    public function __construct(
        private readonly AddressFactory $addressFactory,
    ) {
    }

    public function create(array $data, string $organisationId): ?Model
    {
        $responsible = Responsible::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($responsible->exists) {
            return $responsible;
        }

        $responsible->id = Uuid::generate()->toString();
        $responsible->organisation_id = $organisationId;
        $responsible->import_id = $data['Id'];

        $responsible->name = $data['Naam'];

        $responsible->save();

        $address = $this->addressFactory->create($data, $organisationId);
        $responsible->address()->save($address);

        return $responsible;
    }
}
