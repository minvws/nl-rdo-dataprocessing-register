<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\Media;

use App\Jobs\Media\ValidateMimeType;
use App\Services\Media\MimeTypeService;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Support\Facades\Storage;
use Tests\Helpers\ConfigHelper;
use Throwable;

use function base_path;
use function dispatch_sync;
use function it;
use function sprintf;

it('throws a Exception when the mime type of a media is not in the permitted list', function (): void {
    $disk = ConfigHelper::get('media-library.filesystem_disk');
    Storage::fake($disk);

    $media = Media::factory()
        ->create([
            'file_name' => 'test.png',
            'mime_type' => 'image/png',
        ]);

    copyFileToDisk(
        base_path('tests/Fixtures/Images/test.png'),
        sprintf('%s/%s/%s/test.png', $media->organisation->id, $media->collection_name, $media->uuid),
        $disk,
    );

    ConfigHelper::set('media-library.permitted_file_types.attachment', ['image/jpg']);

    $job = new ValidateMimeType($media);
    $this->expectException(Throwable::class);
    $this->expectExceptionMessage('Mime type is not permitted');

    dispatch_sync($job);
});

it('does not throw an RunTimeException when the mime type of a media is in the permitted list', function (): void {
    $disk = ConfigHelper::get('media-library.filesystem_disk');
    Storage::fake($disk);
    $media = Media::factory()->create([
        'file_name' => 'test.png',
        'mime_type' => 'image/png',
    ]);

    copyFileToDisk(
        base_path('tests/Fixtures/Images/test.png'),
        sprintf('%s/%s/%s/test.png', $media->organisation->id, $media->collection_name, $media->uuid),
        $disk,
    );

    $job = new ValidateMimeType($media);
    dispatch_sync($job);
})->throwsNoExceptions();

it('does not validate mime type if the batch is cancelled', function (): void {
    $this->mock(MimeTypeService::class, function ($mock): void {
        $mock->allows('getMimeType')->never();
    });

    [$job, $batch] = (new ValidateMimeType(Media::factory()->create()))->withFakeBatch();
    $batch->cancel();

    dispatch_sync($job);

    $this->assertTrue($batch->cancelled());
    $this->assertEmpty($batch->added);
});
