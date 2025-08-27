<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factory;
use App\Models\Responsible;

/**
 * @implements Factory<Responsible>
 */
class ResponsibleFactory implements Factory
{
    use DataConverters;

    public function __construct(
        private readonly AddressFactory $addressFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): Responsible
    {
        /** @var Responsible $responsible */
        $responsible = Responsible::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($responsible->exists) {
            return $responsible;
        }

        $responsible->organisation_id = $organisationId;
        $responsible->import_id = $this->toStringOrNull($data, 'Id');
        $responsible->name = $this->toString($data, 'Naam');
        $responsible->save();

        $address = $this->addressFactory->create($data, $organisationId);
        $responsible->address()->save($address);

        return $responsible;
    }
}
