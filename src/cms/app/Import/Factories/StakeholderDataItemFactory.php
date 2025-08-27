<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factory;
use App\Models\StakeholderDataItem;

/**
 * @implements Factory<StakeholderDataItem>
 */
class StakeholderDataItemFactory implements Factory
{
    use DataConverters;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): StakeholderDataItem
    {
        /** @var StakeholderDataItem $stakeholderDataItem */
        $stakeholderDataItem = StakeholderDataItem::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($stakeholderDataItem->exists) {
            return $stakeholderDataItem;
        }

        $stakeholderDataItem->organisation_id = $organisationId;
        $stakeholderDataItem->import_id = $this->toStringOrNull($data, 'Id');

        $stakeholderDataItem->description = $this->toString($data, 'Omschrijving');

        $stakeholderDataItem->collection_purpose = $this->toString($data, 'Verzameldoel');
        $stakeholderDataItem->retention_period = $this->toString($data, 'Bewaartermijn');
        $stakeholderDataItem->is_source_stakeholder = $this->toBoolean($data, 'IsBronBetrokkene');
        $stakeholderDataItem->source_description = $this->toString($data, 'BronOmschrijving');
        $stakeholderDataItem->is_stakeholder_mandatory = $this->toBoolean($data, 'IsBetrokkeneVerplicht');
        $stakeholderDataItem->stakeholder_consequences = $this->toString($data, 'BetrokkeneConsequenties');

        $stakeholderDataItem->save();

        return $stakeholderDataItem;
    }
}
