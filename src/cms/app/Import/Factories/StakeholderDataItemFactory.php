<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\Uuid;
use App\Import\Factory;
use App\Models\StakeholderDataItem;
use Illuminate\Database\Eloquent\Model;

class StakeholderDataItemFactory extends AbstractFactory implements Factory
{
    public function create(array $data, string $organisationId): ?Model
    {
        $stakeholderDataItem = StakeholderDataItem::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($stakeholderDataItem->exists) {
            return $stakeholderDataItem;
        }

        $stakeholderDataItem->id = Uuid::generate()->toString();
        $stakeholderDataItem->organisation_id = $organisationId;
        $stakeholderDataItem->import_id = $this->toStringOrNull($data['Id']);

        $stakeholderDataItem->description = $this->toString($data['Omschrijving']);

        $stakeholderDataItem->collection_purpose = $this->toString($data['Verzameldoel']);
        $stakeholderDataItem->retention_period = $this->toString($data['Bewaartermijn']);
        $stakeholderDataItem->is_source_stakeholder = $this->toBoolean($data['IsBronBetrokkene']);
        $stakeholderDataItem->source_description = $this->toString($data['BronOmschrijving']);
        $stakeholderDataItem->is_stakeholder_mandatory = $this->toBoolean($data['IsBetrokkeneVerplicht']);
        $stakeholderDataItem->stakeholder_consequences = $this->toString($data['BetrokkeneConsequenties']);

        $stakeholderDataItem->save();

        return $stakeholderDataItem;
    }
}
