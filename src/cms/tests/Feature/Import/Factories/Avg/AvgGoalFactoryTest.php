<?php

declare(strict_types=1);

use App\Import\Factories\Avg\AvgGoalFactory;
use App\Models\Avg\AvgGoal;
use App\Models\Organisation;

it('skips the import when model with import_id exists', function (): void {
    $importId = fake()->importId();
    $goal = fake()->word();

    $avgGoal = AvgGoal::factory()
        ->create([
            'import_id' => $importId,
            'goal' => $goal,
        ]);

    /** @var AvgGoalFactory $avgGoalFactory */
    $avgGoalFactory = $this->app->get(AvgGoalFactory::class);
    $avgGoalFactory->create([
        'Id' => $importId, // same import_id
        'Doel' => fake()->unique()->word(), // new value for the goal
    ], $avgGoal->organisation_id);

    $avgGoal->refresh();
    expect($avgGoal->goal)
        ->toBe($goal);
});

it(
    'imports the correct avgGoalLegalBase & remarks',
    function (?string $inputRechtsgrond, ?string $expectedAvgGoalLegalBase, ?string $expectedRemarks): void {
        $organisation = Organisation::factory()->create();

        $importData = [
            'Id' => fake()->importId(),
            'Doel' => fake()->word(),
            'Rechtsgrond' => $inputRechtsgrond,
        ];

        /** @var AvgGoalFactory $avgGoalFactory */
        $avgGoalFactory = $this->app->get(AvgGoalFactory::class);

        /** @var AvgGoal $avgGoal */
        $avgGoal = $avgGoalFactory->create($importData, $organisation->id);

        expect($avgGoal->avg_goal_legal_base)
            ->toBe($expectedAvgGoalLegalBase)
            ->and($avgGoal->remarks)
            ->toBe($expectedRemarks);
    },
)->with([
    // bestaande rechtsgrond
    ['Toestemming betrokkene', 'Toestemming betrokkene', null],
    ['Toestemming betrokkene:', 'Toestemming betrokkene', ''],
    ['Toestemming betrokkene: ', 'Toestemming betrokkene', ''],
    ['Toestemming betrokkene: opmerking', 'Toestemming betrokkene', 'opmerking'],

    // niet bestaande rechtsgrond
    [null, null, null],
    ['', null, ''],
    ['Onbekend opmerking', null, 'Onbekend opmerking'],
    ['Onbekend: opmerking', null, 'Onbekend: opmerking'],
]);
