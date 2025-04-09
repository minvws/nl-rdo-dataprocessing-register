<?php

declare(strict_types=1);

namespace Tests\Feature\Traits\Media;

use App\Enums\Media\MediaGroup;
use App\Models\Document;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

use function it;

it('has the default media collections defined in the MediaGroup enum', function (): void {
    $model = Document::factory()
        ->create();

    foreach ($model->getRegisteredMediaCollections() as $collection) {
        /** @var MediaCollection $collection */
        MediaGroup::from($collection->name);
    }
})->throwsNoExceptions();
