<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Remark;
use App\Models\User;

use function expect;
use function it;

it('can be attached to a AvgProcessorProcessingRecord', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()
        ->create();

    $remark = Remark::factory()->make();
    $avgProcessorProcessingRecord->remarks()->save(
        $remark,
    );

    expect($avgProcessorProcessingRecord->remarks->first()->body)
        ->toBe($remark->body);
});

it('can belong to a user', function (): void {
    $remark = Remark::factory()->make();
    $remark->user()->associate(
        $user = User::factory()->create(),
    );

    expect($remark->user->id)->toBe($user->id);
});

it('can access the inverse of its relationship through the remark_relatable method', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();

    /** @var Remark $remark */
    $remark = Remark::factory()
        ->for($avgProcessorProcessingRecord, 'remarkRelatable')
        ->create();

    expect($remark->remarkRelatable()->first()->id)->toBe($avgProcessorProcessingRecord->id);
});
