<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factories\General\LookupListFactory;
use App\Import\Factory;
use App\Models\ContactPerson;
use App\Models\ContactPersonPosition;

/**
 * @implements Factory<ContactPerson>
 */
class ContactPersonFactory implements Factory
{
    use DataConverters;

    public function __construct(
        private readonly AddressFactory $addressFactory,
        private readonly LookupListFactory $lookupListFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): ?ContactPerson
    {
        /** @var ContactPerson $contactPerson */
        $contactPerson = ContactPerson::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($contactPerson->exists) {
            return $contactPerson;
        }

        $contactPerson->organisation_id = $organisationId;
        $contactPerson->import_id = $this->toStringOrNull($data, 'Id');
        $contactPerson->name = $this->toString($data, 'Naam');
        $contactPerson->email = $this->toString($data, 'Email');
        $contactPerson->phone = $this->toString($data, 'Telefoon');
        $contactPerson->contact_person_position_id = $this->lookupListFactory->create(
            ContactPersonPosition::class,
            $organisationId,
            $this->toStringOrNull($data, 'Functie'),
        )?->id;
        $contactPerson->save();

        $address = $this->addressFactory->create($data, $organisationId);
        $contactPerson->address()->save($address);

        return $contactPerson;
    }
}
