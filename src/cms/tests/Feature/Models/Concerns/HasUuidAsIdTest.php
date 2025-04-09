<?php

declare(strict_types=1);

use App\Components\Uuid\Uuid;
use App\Components\Uuid\UuidInterface;
use App\Models\Concerns\HasUuidAsId;

it('returns a correct value for the id', function (): void {
    $model = new class {
        use HasUuidAsId;

        public function getKey(): UuidInterface
        {
            return Uuid::generate();
        }
    };

    expect($model->getId())
        ->toBeInstanceOf(UuidInterface::class);
});

it('returns a uuid for newUniqueId', function (): void {
    $model = new class {
        use HasUuidAsId;
    };

    $uuid = $model->newUniqueId();

    expect($uuid)
        ->toBeInstanceOf(UuidInterface::class);
});
