<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\Authorization\Role;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Organisation;
use App\Models\User;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\Helpers\ConfigHelper;

use function it;
use function route;
use function sprintf;

it('redirects when no user is logged in', function (): void {
    $this->get('/media/')->assertRedirect('login');
});

it('redirects on a specific object when no user is logged in', function (): void {
    $media = Media::factory()->create();

    $this->get(sprintf('/media/%s', $media->uuid))->assertRedirect('login');
});

it('redirects on a non-existing object when no user is logged in', function (): void {
    $this->get('/media/non-uuid')->assertRedirect('login');
});

it('aborts when the user is not allowed to view the resource', function (): void {
    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::factory()->create();

    $media = Media::factory()
        ->create([
            'model_type' => AvgProcessorProcessingRecord::class,
            'model_id' => $avgProcessorProcessingRecord->id,
        ]);

    $user = $this->createMock(User::class);
    $user->expects($this->once())
        ->method('cannot')
        ->with('view', $this->callback(function (AvgProcessorProcessingRecord $argument) use ($avgProcessorProcessingRecord) {
            return $argument->id === $avgProcessorProcessingRecord->id;
        }))
        ->willReturn(true);

    $this->actingAs($user)->get(route('media.private', $media->uuid))->assertForbidden();
});

it('returns the media in BinaryResponse when the user is allowed to view the resource', function (): void {
    Storage::fake(ConfigHelper::get('media-library.filesystem_disk'));

    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->withValidOtpRegistration()
        ->create();
    $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);
    $user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

    $media = Media::factory()->create([
        'model_id' => $organisation->id,
        'model_type' => Organisation::class,
        'organisation_id' => $organisation->id,
        'mime_type' => 'text/plain',
    ]);

    $filesystem = Storage::disk(ConfigHelper::get('media-library.filesystem_disk'));
    $filesystem->put(sprintf('%s/%s/%s/%s', $organisation->id, $media->collection_name, $media->uuid, $media->file_name), 'test');

    /** @var TestResponse $result */
    $result = $this->actingAs($user)->get(route('media.private', $media->uuid));
    $result->assertOk();
    $result->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    $result->assertHeader('Content-Security-Policy');
});
