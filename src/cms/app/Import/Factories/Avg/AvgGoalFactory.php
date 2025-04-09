<?php

declare(strict_types=1);

namespace App\Import\Factories\Avg;

use App\Components\Uuid\Uuid;
use App\Import\Factories\AbstractFactory;
use App\Import\Factory;
use App\Models\Avg\AvgGoal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use function __;

class AvgGoalFactory extends AbstractFactory implements Factory
{
    public function create(array $data, string $organisationId): ?Model
    {
        $avgGoal = AvgGoal::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($avgGoal->exists) {
            return $avgGoal;
        }

        $avgGoal->id = Uuid::generate()->toString();
        $avgGoal->organisation_id = $organisationId;
        $avgGoal->import_id = $this->toStringOrNull($data['Id']);

        $avgGoal->goal = $this->toString($data['Doel']);
        $avgGoal = $this->setAvgGoalLegalBaseAndRemarks($avgGoal, $data['Rechtsgrond']);

        $avgGoal->save();

        return $avgGoal;
    }

    private function setAvgGoalLegalBaseAndRemarks(AvgGoal $avgGoal, ?string $inputRechtsgrond): AvgGoal
    {
        if ($inputRechtsgrond === null) {
            $avgGoal->avg_goal_legal_base = null;
            $avgGoal->remarks = null;

            return $avgGoal;
        }

        $avgGoalLegalBase = self::getDescriptionFromOptions(__('avg_goal_legal_base.options'), $inputRechtsgrond);
        if ($avgGoalLegalBase !== null) {
            $avgGoal->avg_goal_legal_base = $avgGoalLegalBase;

            return $avgGoal;
        }

        $inputRechtsgrondStringable = Str::of($inputRechtsgrond);
        if (!$inputRechtsgrondStringable->contains(':')) {
            $avgGoal->avg_goal_legal_base = null;
            $avgGoal->remarks = $inputRechtsgrondStringable->toString();

            return $avgGoal;
        }

        $avgGoalLegalBase = self::getDescriptionFromOptions(
            __('avg_goal_legal_base.options'),
            $inputRechtsgrondStringable->before(':')->toString(),
        );

        if ($avgGoalLegalBase !== null) {
            $avgGoal->avg_goal_legal_base = $avgGoalLegalBase;
            $avgGoal->remarks = $inputRechtsgrondStringable->after(':')->trim()->toString();

            return $avgGoal;
        }

        $avgGoal->avg_goal_legal_base = null;
        $avgGoal->remarks = $inputRechtsgrondStringable->toString();

        return $avgGoal;
    }
}
