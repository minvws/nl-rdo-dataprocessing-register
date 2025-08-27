<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factory;
use App\Models\Stakeholder;
use Webmozart\Assert\Assert;

/**
 * @implements Factory<Stakeholder>
 */
class StakeholderFactory implements Factory
{
    use DataConverters;

    public function __construct(
        private readonly StakeholderDataItemFactory $stakeholderDataItemFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): Stakeholder
    {
        /** @var Stakeholder $stakeholder */
        $stakeholder = Stakeholder::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($stakeholder->exists) {
            return $stakeholder;
        }

        $stakeholder->organisation_id = $organisationId;
        $stakeholder->import_id = $this->toStringOrNull($data, 'Id');

        $stakeholder->description = $this->toString($data, 'Omschrijving');
        $stakeholder->race_or_ethnicity = $this->toBoolean($data, 'RasOfEtniciteit');
        $stakeholder->political_attitude = $this->toBoolean($data, 'PolitiekeGezindheid');
        $stakeholder->faith_or_belief = $this->toBoolean($data, 'GodsdienstOfLevensovertuiging');
        $stakeholder->trade_association_membership = $this->toBoolean($data, 'LidmaatschapVakvereniging');
        $stakeholder->genetic = $this->toBoolean($data, 'Genetisch');
        $stakeholder->biometric = $this->toBoolean($data, 'Biometrisch');
        $stakeholder->health = $this->toBoolean($data, 'Gezondheid');
        $stakeholder->sexual_life = $this->toBoolean($data, 'SeksueleLeven');
        $stakeholder->criminal_law = $this->toBoolean($data, 'Strafrechtelijk');
        $stakeholder->citizen_service_numbers = false;

        $stakeholder->save();

        $gegevens = $this->toArray($data, 'Gegevens');
        foreach ($gegevens as $gegeven) {
            Assert::isMap($gegeven);
            $stakeholderDataItem = $this->stakeholderDataItemFactory->create($gegeven, $organisationId);

            $stakeholder->stakeholderDataItems()->save($stakeholderDataItem);
        }

        return $stakeholder;
    }
}
