<?php

declare(strict_types=1);

namespace App\Import\Factories\Wpg;

use App\Components\Uuid\UuidInterface;
use App\Import\Factories\Concerns\DataConverters;
use App\Import\Factory;
use App\Models\Wpg\WpgGoal;

/**
 * @implements Factory<WpgGoal>
 */
class WpgGoalFactory implements Factory
{
    use DataConverters;

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data, UuidInterface $organisationId): WpgGoal
    {
        /** @var WpgGoal $wpgGoal */
        $wpgGoal = WpgGoal::firstOrNew([
            'import_id' => $data['Id'],
            'organisation_id' => $organisationId,
        ]);

        if ($wpgGoal->exists) {
            return $wpgGoal;
        }

        $wpgGoal->organisation_id = $organisationId;
        $wpgGoal->import_id = $this->toStringOrNull($data, 'Id');

        $wpgGoal->description = $this->toString($data, 'Omschrijving');
        $wpgGoal->article_8 = $this->toBoolean($data, 'Artikel8');
        $wpgGoal->article_9 = $this->toBoolean($data, 'Artikel9');
        $wpgGoal->article_10_1a = $this->toBoolean($data, 'Artikel10Lid1A');
        $wpgGoal->article_10_1b = $this->toBoolean($data, 'Artikel10Lid1B');
        $wpgGoal->article_10_1c = $this->toBoolean($data, 'Artikel10Lid1C');
        $wpgGoal->article_12 = $this->toBoolean($data, 'Artikel12');
        $wpgGoal->article_13_1 = $this->toBoolean($data, 'Artikel13Lid1');
        $wpgGoal->article_13_2 = $this->toBoolean($data, 'Artikel13Lid2');
        $wpgGoal->article_13_3 = $this->toBoolean($data, 'Artikel13Lid3');
        $wpgGoal->explanation = $this->toString($data, 'Toelichting');

        return $wpgGoal;
    }
}
