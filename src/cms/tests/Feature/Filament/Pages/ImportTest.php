<?php

declare(strict_types=1);

use App\Filament\Pages\Import;
use App\Import\ImportFailedException;
use App\Import\ZipImporter;
use App\Services\BuildContextService;
use Filament\Notifications\Notification;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tests\Helpers\Model\OrganisationTestHelper;

it('loads the import page', function (): void {
    $organisation = OrganisationTestHelper::create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(Import::getUrl(tenant: $organisation))
        ->assertSee(__('import.help'));
});

it('validates files input', function (): void {
    $this->asFilamentUser()
        ->createLivewireTestable(Import::class)
        ->fillForm([
            'files' => [],
        ])
        ->call('submit')
        ->assertHasFormErrors(['files' => 'required']);
});

it('can submit the files', function (): void {
    $this->asFilamentUser();

    $filename = sprintf('%s.zip', fake()->slug());
    $importFile = new TemporaryUploadedFile($filename, '');

    $buildContextService = $this->app->get(BuildContextService::class);

    $zipImporter = $this->mock(ZipImporter::class)
        ->shouldReceive('importFiles')
        ->once()
        ->withSomeOfArgs([$importFile])
        ->getMock();

    prepareImportZip($filename);

    $importPage = new Import();
    $importPage->files = [$importFile];
    $importPage->submit($buildContextService, $zipImporter);

    Notification::assertNotified(__('import.upload_success'));
});

it('shows import failed notification on failure', function (): void {
    $this->asFilamentUser();

    $filename = sprintf('%s.zip', fake()->slug());
    $importFile = new TemporaryUploadedFile($filename, '');

    $buildContextService = $this->app->get(BuildContextService::class);

    $zipImporter = $this->mock(ZipImporter::class)
        ->shouldReceive('importFiles')
        ->once()
        ->withSomeOfArgs([$importFile])
        ->andThrow(new ImportFailedException())
        ->getMock();

    prepareImportZip($filename);

    $importPage = new Import();
    $importPage->files = [
        $importFile,
    ];
    $importPage->submit($buildContextService, $zipImporter);

    Notification::assertNotified(__('import.failed'));
});
