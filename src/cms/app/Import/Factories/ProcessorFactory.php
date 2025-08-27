<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factory;
use App\Models\Processor;

/**
 * @implements Factory<Processor>
 */
class ProcessorFactory implements Factory
{
    use DataConverters;

    public function __construct(
        private readonly AddressFactory $addressFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): Processor
    {
        /** @var Processor $processor */
        $processor = Processor::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($processor->exists) {
            return $processor;
        }

        $processor->organisation_id = $organisationId;
        $processor->import_id = $this->toStringOrNull($data, 'Id');
        $processor->name = $this->toString($data, 'Naam');
        $processor->email = $this->toString($data, 'Email');
        $processor->phone = $this->toString($data, 'Telefoon');
        $processor->save();

        $address = $this->addressFactory->create($data, $organisationId);
        $processor->address()->save($address);

        return $processor;
    }
}
