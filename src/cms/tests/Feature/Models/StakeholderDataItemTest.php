<?php

declare(strict_types=1);

use App\Models\Stakeholder;
use App\Models\StakeholderDataItem;

it('has stakeholders', function (): void {
    $stakeholder = Stakeholder::factory()
        ->create();
    $stakeholderDataItem = StakeholderDataItem::factory()
        ->hasAttached($stakeholder)
        ->create();

    expect($stakeholderDataItem->stakeholders()->first()->id)
        ->toBe($stakeholder->id);
});
