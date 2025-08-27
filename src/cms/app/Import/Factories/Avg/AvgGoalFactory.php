<?php

declare(strict_types=1);

namespace App\Import\Factories\Avg;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factory;
use App\Models\Avg\AvgGoal;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function __;

/**
 * @implements Factory<AvgGoal>
 */
class AvgGoalFactory implements Factory
{
    use DataConverters;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): ?AvgGoal
    {
        /** @var AvgGoal $avgGoal */
        $avgGoal = AvgGoal::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($avgGoal->exists) {
            return $avgGoal;
        }

        $avgGoal->organisation_id = $organisationId;
        $avgGoal->import_id = $this->toStringOrNull($data, 'Id');
        $avgGoal->goal = $this->toString($data, 'Doel');
        $avgGoal = $this->setAvgGoalLegalBaseAndRemarks($avgGoal, $this->toStringOrNull($data, 'Rechtsgrond'));
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

        $avgGoalLegalBaseOptions = __('avg_goal_legal_base.options');
        Assert::allString($avgGoalLegalBaseOptions);
        $avgGoalLegalBase = self::getDescriptionFromOptions($avgGoalLegalBaseOptions, $inputRechtsgrond);
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

        $avgGoalLegalBase = self::getDescriptionFromOptions($avgGoalLegalBaseOptions, $inputRechtsgrondStringable->before(':')->toString());

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
