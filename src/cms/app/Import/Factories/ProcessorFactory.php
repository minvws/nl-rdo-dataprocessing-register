<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\Uuid;
use App\Import\Factory;
use App\Models\Processor;
use Illuminate\Database\Eloquent\Model;

class ProcessorFactory extends AbstractFactory implements Factory
{
    public function __construct(
        private readonly AddressFactory $addressFactory,
    ) {
    }

    public function create(array $data, string $organisationId): ?Model
    {
        $processor = Processor::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($processor->exists) {
            return $processor;
        }

        $processor->id = Uuid::generate()->toString();
        $processor->organisation_id = $organisationId;
        $processor->import_id = $this->toStringOrNull($data['Id']);

        $processor->name = $this->toString($data['Naam']);
        $processor->email = $this->toString($data['Email']);
        $processor->phone = $this->toString($data['Telefoon']);

        $processor->save();

        $address = $this->addressFactory->create($data, $organisationId);
        $processor->address()->save($address);

        return $processor;
    }
}
