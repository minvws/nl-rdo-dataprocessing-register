<?php

declare(strict_types=1);

namespace Tests\Feature\Observers;

use App\Events\Models\PublishableEvent;
use App\Models\Stakeholder;
use App\Models\StakeholderDataItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

use function expect;
use function fake;
use function it;

it('deletes stakeholder data items on delete', function (): void {
    expect(StakeholderDataItem::count())
        ->toBe(0);

    $number = fake()->numberBetween(1, 3);
    $stakeholder = Stakeholder::factory()
        ->withStakeholderDataItems($number)
        ->create();

    expect(StakeholderDataItem::count())
        ->toBe($number);

    $stakeholder->delete();

    expect(StakeholderDataItem::count())
        ->toBe(0);
});

it('resets special_collected_data_explanation on save', function (): void {
    Event::fake(PublishableEvent::class);

    $avgProcessorProcessingRecord = Stakeholder::factory()
        ->create([
            'biometric' => true,
            'faith_or_belief' => true,
            'genetic' => true,
            'health' => true,
            'political_attitude' => true,
            'race_or_ethnicity' => true,
            'sexual_life' => true,
            'trade_association_membership' => true,
            'special_collected_data_explanation' => fake()->word(),
        ]);

    $avgProcessorProcessingRecord->biometric = false;
    $avgProcessorProcessingRecord->faith_or_belief = false;
    $avgProcessorProcessingRecord->genetic = false;
    $avgProcessorProcessingRecord->health = false;
    $avgProcessorProcessingRecord->political_attitude = false;
    $avgProcessorProcessingRecord->race_or_ethnicity = false;
    $avgProcessorProcessingRecord->sexual_life = false;
    $avgProcessorProcessingRecord->trade_association_membership = false;
    $avgProcessorProcessingRecord->save();
    $avgProcessorProcessingRecord->refresh();

    expect($avgProcessorProcessingRecord->special_collected_data_explanation)
        ->toBeNull();
});

it('does not reset special_collected_data_explanation on save if one field true', function (): void {
    Event::fake(PublishableEvent::class);

    $word = fake()->word();

    $avgProcessorProcessingRecord = Stakeholder::factory()
        ->create([
            'biometric' => true,
            'faith_or_belief' => true,
            'genetic' => true,
            'health' => true,
            'political_attitude' => true,
            'race_or_ethnicity' => true,
            'sexual_life' => true,
            'trade_association_membership' => true,
            'special_collected_data_explanation' => $word,
        ]);

    $fields = new Collection([
        'biometric',
        'faith_or_belief',
        'genetic',
        'health',
        'political_attitude',
        'race_or_ethnicity',
        'sexual_life',
        'trade_association_membership',
    ]);

    foreach ($fields as $field) {
        $avgProcessorProcessingRecord->$field = false;
    }

    // set one random field back to true
    $field = $fields->random(1)->first();
    $avgProcessorProcessingRecord->$field = true;

    $avgProcessorProcessingRecord->save();
    $avgProcessorProcessingRecord->refresh();

    expect($avgProcessorProcessingRecord->special_collected_data_explanation)
        ->toEqual($word);
});
