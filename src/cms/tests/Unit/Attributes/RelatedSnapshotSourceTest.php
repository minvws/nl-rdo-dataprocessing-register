<?php

declare(strict_types=1);

use App\Attributes\RelatedSnapshotSource;
use App\Models\System;

it('can be initiated', function (): void {
    $model = new RelatedSnapshotSource(System::class);

    expect($model)
        ->toBeInstanceOf(RelatedSnapshotSource::class);
});
