<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;
use App\Filament\Pages\Import;
use App\Import\ImportFailedException;
use App\Import\ZipImporter;
use Filament\Notifications\Notification;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

use function Pest\Livewire\livewire;

it('loads the import page', function (): void {
    $this->get(Import::getUrl(tenant: $this->organisation))
        ->assertSee(__('import.help'));
});

it('validates files input', function (): void {
    $this->user->assignOrganisationRole(Role::PRIVACY_OFFICER, $this->organisation);

    livewire(Import::class)
        ->fillForm([
            'files' => [],
        ])
        ->call('submit')
        ->assertHasFormErrors(['files' => 'required']);
});

it('can submit the files', function (): void {
    $importFile = new TemporaryUploadedFile('import.zip', '');

    $zipImporter = $this->createMock(ZipImporter::class);
    $zipImporter->expects($this->once())
        ->method('importFiles')
        ->with([$importFile]);

    prepareImportZip('import.zip');

    $importPage = new Import();
    $importPage->files = [
        $importFile,
    ];
    $importPage->submit($zipImporter);

    Notification::assertNotified(__('import.upload_success'));
});

it('shows import failed notification on failure', function (): void {
    $importFile = new TemporaryUploadedFile('import.zip', '');

    $zipImporter = $this->createMock(ZipImporter::class);
    $zipImporter->expects($this->once())
        ->method('importFiles')
        ->with([$importFile])
        ->willThrowException(new ImportFailedException());

    prepareImportZip('import.zip');

    $importPage = new Import();
    $importPage->files = [
        $importFile,
    ];
    $importPage->submit($zipImporter);

    Notification::assertNotified(__('import.failed'));
});
