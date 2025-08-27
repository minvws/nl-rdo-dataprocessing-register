<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Filament\Exports\AvgResponsibleProcessingRecordExporter;
use App\Models\Organisation;
use App\Models\User;
use Filament\Actions\Exports\Downloaders\CsvDownloader;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Tests\Helpers\ConfigTestHelper;
use Tests\Helpers\Model\UserTestHelper;

use function fake;
use function it;
use function response;
use function sprintf;

it('can download an export', function (): void {
    $user = UserTestHelper::create();

    $export = Export::create([
        'completed_at' => fake()->dateTime(),
        'file_disk' => ConfigTestHelper::get('filament.default_filesystem_disk'),
        'file_name' => fake()->slug(),
        'exporter' => AvgResponsibleProcessingRecordExporter::class,
        'processed_rows' => fake()->numberBetween(1, 9),
        'total_rows' => fake()->numberBetween(1, 9),
        'successful_rows' => fake()->numberBetween(1, 9),
        'user_id' => $user->id,
    ]);

    $this->mock(CsvDownloader::class)
        ->shouldReceive('__invoke')
        ->once()
        ->andReturn(response()->streamDownload(static function () {
            return '';
        }));

    $this->asFilamentUser($user)
        ->get(sprintf('/filament/exports/%s/download?format=csv', $export->id))
        ->assertOk();
});

it('can not download from other user', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()
        ->hasAttached($organisation)
        ->withValidOtpRegistration()
        ->create();
    $this->be($user);
    Filament::setTenant($organisation);

    $otherUser = User::factory()->create();

    $export = Export::create([
        'completed_at' => fake()->dateTime(),
        'file_disk' => ConfigTestHelper::get('filament.default_filesystem_disk'),
        'file_name' => fake()->slug(),
        'exporter' => AvgResponsibleProcessingRecordExporter::class,
        'processed_rows' => fake()->numberBetween(1, 9),
        'total_rows' => fake()->numberBetween(1, 9),
        'successful_rows' => fake()->numberBetween(1, 9),
        'user_id' => $otherUser->id,
    ]);

    $this->get(sprintf('/filament/exports/%s/download?format=csv', $export->id))
        ->assertForbidden();
});
