<?php

declare(strict_types=1);

use App\Models\Algorithm\AlgorithmRecord;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\DataBreachRecord;
use App\Models\FgRemark;
use App\Models\Wpg\WpgProcessingRecord;

it('can retrieve the body', function (): void {
    $body = fake()->sentence();

    $fgRemark = FgRemark::factory()
        ->create([
            'body' => $body,
        ]);

    expect($fgRemark->body)
        ->toBe($body);
});

it('has algorithm records', function (): void {
    $fgRemark = FgRemark::factory()
        ->for(AlgorithmRecord::factory())
        ->create();

    expect($fgRemark->algorithmRecord()->first())
        ->toBeInstanceOf(AlgorithmRecord::class);
});

it('has avg processor processing records', function (): void {
    $fgRemark = FgRemark::factory()
        ->for(AvgProcessorProcessingRecord::factory())
        ->create();

    expect($fgRemark->avgProcessorProcessingRecord()->first())
        ->toBeInstanceOf(AvgProcessorProcessingRecord::class);
});

it('has avg responsible processing records', function (): void {
    $fgRemark = FgRemark::factory()
        ->for(AvgResponsibleProcessingRecord::factory())
        ->create();

    expect($fgRemark->avgResponsibleProcessingRecord()->first())
        ->toBeInstanceOf(AvgResponsibleProcessingRecord::class);
});

it('has databreach records', function (): void {
    $fgRemark = FgRemark::factory()
        ->for(DataBreachRecord::factory())
        ->create();

    expect($fgRemark->dataBreachRecord()->first())
        ->toBeInstanceOf(DataBreachRecord::class);
});

it('has wpg processing records', function (): void {
    $fgRemark = FgRemark::factory()
        ->for(WpgProcessingRecord::factory())
        ->create();

    expect($fgRemark->wpgProcessingRecord()->first())
        ->toBeInstanceOf(WpgProcessingRecord::class);
});
