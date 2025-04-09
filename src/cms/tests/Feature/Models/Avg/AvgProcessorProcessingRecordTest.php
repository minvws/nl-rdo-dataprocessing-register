<?php

declare(strict_types=1);

namespace Tests\Feature\Models\Avg;

use App\Models\Avg\AvgGoal;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\ContactPerson;
use App\Models\DataBreachRecord;
use App\Models\FgRemark;
use App\Models\Organisation;
use App\Models\Processor;
use App\Models\Responsible;
use App\Models\System;

use function expect;
use function it;

it('can have contact persons', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();
    $contactPerson = ContactPerson::factory()->create();
    $avgProcessorProcessingRecord->contactPersons()->attach($contactPerson);

    expect($avgProcessorProcessingRecord->contactPersons()->first())->toBeInstanceOf(ContactPerson::class);
});

it('can have data breach records', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();
    $dataBreachRecord = DataBreachRecord::factory()->create();
    $avgProcessorProcessingRecord->dataBreachRecords()->attach($dataBreachRecord);

    expect($avgProcessorProcessingRecord->dataBreachRecords()->first())->toBeInstanceOf(DataBreachRecord::class);
});

it('can have processors', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();
    $processor = Processor::factory()->create();
    $avgProcessorProcessingRecord->processors()->attach($processor);

    expect($avgProcessorProcessingRecord->processors()->first())->toBeInstanceOf(Processor::class);
});

it('can have responsibles', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();
    $responsible = Responsible::factory()->create();
    $avgProcessorProcessingRecord->responsibles()->attach($responsible);

    expect($avgProcessorProcessingRecord->responsibles()->first())->toBeInstanceOf(Responsible::class);
});

it('can have avg goals', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();
    $avgGoal = AvgGoal::factory()->create();
    $avgProcessorProcessingRecord->avgGoals()->attach($avgGoal);

    expect($avgProcessorProcessingRecord->avgGoals()->first())->toBeInstanceOf(AvgGoal::class);
});

it('can have an organisation', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();
    $organisation = Organisation::factory()->create();
    $avgProcessorProcessingRecord->organisation()->associate($organisation);

    expect($avgProcessorProcessingRecord->organisation)->toBeInstanceOf(Organisation::class);
});

it('can belong to a system', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();
    $system = System::factory()->create();
    $avgProcessorProcessingRecord->systems()->attach($system);

    expect($avgProcessorProcessingRecord->systems()->first())->toBeInstanceOf(System::class);
});

it('can have a fg remark', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();
    $fgRemark = FgRemark::factory()->create();
    $avgProcessorProcessingRecord->fgRemark()->save($fgRemark);

    expect($avgProcessorProcessingRecord->fgRemark)->toBeInstanceOf(FgRemark::class);
});
