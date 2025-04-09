<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\Uuid;
use App\Import\Factory;
use App\Models\Address;

class AddressFactory extends AbstractFactory implements Factory
{
    public function create(array $data, string $organisationId): Address
    {
        $address = new Address();
        $address->id = Uuid::generate();

        $address->address = $this->toString($data['Adres']);
        $address->postal_code = $this->toString($data['Postcode']);
        $address->city = $this->toString($data['Plaats']);
        $address->country = $this->toString($data['Land']);

        $address->postbox = $this->toString($data['Postbus']);
        $address->postbox_postal_code = $this->toString($data['PostbusPostcode']);
        $address->postbox_city = $this->toString($data['PostbusPlaats']);
        $address->postbox_country = $this->toString($data['PostbusLand']);

        return $address;
    }
}
