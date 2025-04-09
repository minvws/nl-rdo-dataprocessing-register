<?php

declare(strict_types=1);

namespace App\Import\Factories;

use App\Components\Uuid\Uuid;
use App\Import\Factory;
use App\Models\Stakeholder;
use App\Models\StakeholderDataItem;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

class StakeholderFactory extends AbstractFactory implements Factory
{
    public function __construct(
        private readonly StakeholderDataItemFactory $stakeholderDataItemFactory,
    ) {
    }

    public function create(array $data, string $organisationId): ?Model
    {
        $stakeholder = Stakeholder::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($stakeholder->exists) {
            return $stakeholder;
        }

        $stakeholder->id = Uuid::generate()->toString();
        $stakeholder->organisation_id = $organisationId;
        $stakeholder->import_id = $this->toStringOrNull($data['Id']);

        $stakeholder->description = $this->toString($data['Omschrijving']);
        $stakeholder->race_or_ethnicity = $this->toBoolean($data['RasOfEtniciteit']);
        $stakeholder->political_attitude = $this->toBoolean($data['PolitiekeGezindheid']);
        $stakeholder->faith_or_belief = $this->toBoolean($data['GodsdienstOfLevensovertuiging']);
        $stakeholder->trade_association_membership = $this->toBoolean($data['LidmaatschapVakvereniging']);
        $stakeholder->genetic = $this->toBoolean($data['Genetisch']);
        $stakeholder->biometric = $this->toBoolean($data['Biometrisch']);
        $stakeholder->health = $this->toBoolean($data['Gezondheid']);
        $stakeholder->sexual_life = $this->toBoolean($data['SeksueleLeven']);
        $stakeholder->criminal_law = $this->toBoolean($data['Strafrechtelijk']);
        $stakeholder->citizen_service_numbers = false;

        $stakeholder->save();

        foreach ($data['Gegevens'] as $data) {
            $stakeholderDataItem = $this->stakeholderDataItemFactory->create($data, $organisationId);
            Assert::isInstanceOf($stakeholderDataItem, StakeholderDataItem::class);

            $stakeholder->stakeholderDataItems()->save($stakeholderDataItem);
        }

        return $stakeholder;
    }
}
