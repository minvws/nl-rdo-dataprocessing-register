<?php

declare(strict_types=1);

namespace Tests\Feature\Observers;

use App\Models\Avg\AvgResponsibleProcessingRecord;

use function __;
use function expect;
use function fake;
use function it;

it('resets processor data on save', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->withProcessors()
        ->create([
            'has_processors' => true,
        ]);

    $avgResponsibleProcessingRecord->has_processors = false;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->processors)
        ->toHaveCount(0);
});

it('resets decision making data on save', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'decision_making' => true,
            'logic' => fake()->word(),
            'importance_consequences' => fake()->word(),
        ]);

    $avgResponsibleProcessingRecord->decision_making = false;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->logic)->toBeNull()
        ->and($avgResponsibleProcessingRecord->importance_consequences)->toBeNull();
});

it('resets systems data on save', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->withSystems()
        ->create([
            'has_systems' => true,
        ]);

    $avgResponsibleProcessingRecord->has_systems = false;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->systems)
        ->toHaveCount(0);
});

it('resets security data on save', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'has_security' => true,
            'measures_implemented' => true,
            'other_measures' => true,
            'measures_description' => fake()->word(),
            'has_pseudonymization' => true,
            'pseudonymization' => fake()->word(),
        ]);

    $avgResponsibleProcessingRecord->has_security = false;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->measures_implemented)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->other_measures)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->measures_description)->toBeNull()
        ->and($avgResponsibleProcessingRecord->has_pseudonymization)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->pseudonymization)->toBeNull();
});

it('resets passthrough data on save', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'outside_eu' => true,
            'country' => fake()->word(),
            'outside_eu_protection_level' => fake()->boolean(),
            'outside_eu_description' => fake()->word(),
            'outside_eu_protection_level_description' => fake()->word(),
        ]);

    $avgResponsibleProcessingRecord->outside_eu = false;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->country)->toBeNull()
        ->and($avgResponsibleProcessingRecord->outside_eu_protection_level)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->outside_eu_description)->toBeNull()
        ->and($avgResponsibleProcessingRecord->outside_eu_protection_level_description)->toBeNull();
});

it('resets passthrough data if outside_eu_protection_level is true on save', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'outside_eu' => true,
            'country' => fake()->word(),
            'outside_eu_protection_level' => false,
            'outside_eu_description' => fake()->word(),
            'outside_eu_protection_level_description' => fake()->word(),
        ]);

    $avgResponsibleProcessingRecord->outside_eu_protection_level = true;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->outside_eu_protection_level_description)
        ->toBeNull();
});

it('resets passthrough data if outside_eu_protection_level is false on save', function (): void {
    $word = fake()->word();

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->createQuietly([ // quietly to make sure record is in "invalid" state
            'outside_eu' => true,
            'country' => fake()->word(),
            'outside_eu_protection_level' => true,
            'outside_eu_description' => fake()->word(),
            'outside_eu_protection_level_description' => $word,
        ]);

    $avgResponsibleProcessingRecord->outside_eu_protection_level = false;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->outside_eu_protection_level_description)
        ->toBe($word);
});

it('resets passthrough country_other if not other on save', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'outside_eu' => true,
            'country' => __('general.country_other'),
            'country_other' => fake()->word(),
        ]);

    $avgResponsibleProcessingRecord->country = fake()->word();
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->country_other)
        ->toBeNull();
});

it('resets gebDpia-fields for geb_dpia_executed', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'geb_dpia_executed' => false,
            'geb_dpia_automated' => true,
            'geb_dpia_large_scale_processing' => true,
            'geb_dpia_large_scale_monitoring' => true,
            'geb_dpia_list_required' => true,
            'geb_dpia_criteria_wp248' => true,
            'geb_dpia_high_risk_freedoms' => true,
        ]);

    $avgResponsibleProcessingRecord->geb_dpia_executed = true;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->geb_dpia_executed)->toBeTrue()
        ->and($avgResponsibleProcessingRecord->geb_dpia_automated)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_processing)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_list_required)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_criteria_wp248)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms)->toBeFalse();
});

it('resets gebDpia-fields for geb_dpia_automated', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'geb_dpia_executed' => false,
            'geb_dpia_automated' => false,
            'geb_dpia_large_scale_processing' => true,
            'geb_dpia_large_scale_monitoring' => true,
            'geb_dpia_list_required' => true,
            'geb_dpia_criteria_wp248' => true,
            'geb_dpia_high_risk_freedoms' => true,
        ]);

    $avgResponsibleProcessingRecord->geb_dpia_automated = true;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->geb_dpia_executed)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_automated)->toBeTrue()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_processing)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_list_required)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_criteria_wp248)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms)->toBeFalse();
});

it('resets gebDpia-fields for geb_dpia_large_scale_processing', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'geb_dpia_executed' => false,
            'geb_dpia_automated' => false,
            'geb_dpia_large_scale_processing' => false,
            'geb_dpia_large_scale_monitoring' => true,
            'geb_dpia_list_required' => true,
            'geb_dpia_criteria_wp248' => true,
            'geb_dpia_high_risk_freedoms' => true,
        ]);

    $avgResponsibleProcessingRecord->geb_dpia_large_scale_processing = true;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->geb_dpia_executed)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_automated)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_processing)->toBeTrue()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_list_required)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_criteria_wp248)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms)->toBeFalse();
});

it('resets gebDpia-fields for geb_dpia_large_scale_monitoring', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'geb_dpia_executed' => false,
            'geb_dpia_automated' => false,
            'geb_dpia_large_scale_processing' => false,
            'geb_dpia_large_scale_monitoring' => false,
            'geb_dpia_list_required' => true,
            'geb_dpia_criteria_wp248' => true,
            'geb_dpia_high_risk_freedoms' => true,
        ]);

    $avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring = true;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->geb_dpia_executed)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_automated)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_processing)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring)->toBeTrue()
        ->and($avgResponsibleProcessingRecord->geb_dpia_list_required)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_criteria_wp248)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms)->toBeFalse();
});

it('resets gebDpia-fields for geb_dpia_list_required', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'geb_dpia_executed' => false,
            'geb_dpia_automated' => false,
            'geb_dpia_large_scale_processing' => false,
            'geb_dpia_large_scale_monitoring' => false,
            'geb_dpia_list_required' => false,
            'geb_dpia_criteria_wp248' => true,
            'geb_dpia_high_risk_freedoms' => true,
        ]);

    $avgResponsibleProcessingRecord->geb_dpia_list_required = true;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->geb_dpia_executed)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_automated)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_processing)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_list_required)->toBeTrue()
        ->and($avgResponsibleProcessingRecord->geb_dpia_criteria_wp248)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms)->toBeFalse();
});

it('resets gebDpia-fields for geb_dpia_criteria_wp248', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'geb_dpia_executed' => false,
            'geb_dpia_automated' => false,
            'geb_dpia_large_scale_processing' => false,
            'geb_dpia_large_scale_monitoring' => false,
            'geb_dpia_list_required' => false,
            'geb_dpia_criteria_wp248' => false,
            'geb_dpia_high_risk_freedoms' => true,
        ]);

    $avgResponsibleProcessingRecord->geb_dpia_criteria_wp248 = true;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->geb_dpia_executed)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_automated)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_processing)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_list_required)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_criteria_wp248)->toBeTrue()
        ->and($avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms)->toBeFalse();
});

it('resets gebDpia-fields for geb_dpia_high_risk_freedoms', function (): void {
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->create([
            'geb_dpia_executed' => false,
            'geb_dpia_automated' => false,
            'geb_dpia_large_scale_processing' => false,
            'geb_dpia_large_scale_monitoring' => false,
            'geb_dpia_list_required' => false,
            'geb_dpia_criteria_wp248' => false,
            'geb_dpia_high_risk_freedoms' => false,
        ]);

    $avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms = true;
    $avgResponsibleProcessingRecord->save();
    $avgResponsibleProcessingRecord->refresh();

    expect($avgResponsibleProcessingRecord->geb_dpia_executed)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_automated)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_processing)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_large_scale_monitoring)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_list_required)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_criteria_wp248)->toBeFalse()
        ->and($avgResponsibleProcessingRecord->geb_dpia_high_risk_freedoms)->toBeTrue();
});
