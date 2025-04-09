<?php

declare(strict_types=1);

use App\Import\ImportFailedException;
use App\Import\ZipImporter;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Organisation;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

it('can process a zip file', function (): void {
    $importIdThatExistsInTheTestZipFile = '12536';
    $numberThatExistsInTheTestZipFile = 'M12536';

    prepareImportZip('import.zip');

    $organisation = Organisation::factory()
        ->create();
    $importFile = new TemporaryUploadedFile('import.zip', '');

    /** @var ZipImporter $zipImporter */
    $zipImporter = $this->app->get(ZipImporter::class);
    $zipImporter->importFiles([$importFile], fake()->uuid(), $organisation->id);

    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::query()
        ->where('import_id', $importIdThatExistsInTheTestZipFile)
        ->firstOrFail();
    expect($avgProcessorProcessingRecord->import_number)
        ->toBe($numberThatExistsInTheTestZipFile);
});

it('fails if there are too many files in the zip', function (): void {
    $organisation = Organisation::factory()->create();
    $importFile = new TemporaryUploadedFile('import.zip', '');

    $zipArchive = $this->createMock(ZipArchive::class);
    $zipArchive->expects($this->once())
        ->method('count')
        ->willReturn(2);

    $zipImporter = new ZipImporter($zipArchive, [], [], 1, 1);

    $this->expectException(ImportFailedException::class);
    $this->expectExceptionMessage('too many files in zip');
    $zipImporter->importFiles([$importFile], fake()->uuid(), $organisation->id);
});

it('fails if the filesize in the zip is too large', function (): void {
    $importFile = new TemporaryUploadedFile('import.zip', '');
    $organisation = Organisation::factory()->create();

    $zipArchive = $this->createMock(ZipArchive::class);
    $zipArchive->expects($this->once())
        ->method('count')
        ->willReturn(1);
    $zipArchive->expects($this->once())
        ->method('statIndex')
        ->with(0)
        ->willReturn(['size' => (1_024 * 1_024) + 1]);

    $zipImporter = new ZipImporter($zipArchive, [], [], 1, 1);

    $this->expectException(ImportFailedException::class);
    $this->expectExceptionMessage('filesize too large in zip');
    $zipImporter->importFiles([$importFile], fake()->uuid(), $organisation->id);
});

it('skips the import if factory cannot be found', function (): void {
    $organisation = Organisation::factory()->create();
    $filename = sprintf('%s.json', fake()->slug());
    $importFile = new TemporaryUploadedFile('import.zip', '');

    $zipArchive = $this->createMock(ZipArchive::class);
    $zipArchive->expects($this->once())
        ->method('count')
        ->willReturn(1);
    $zipArchive->expects($this->once())
        ->method('statIndex')
        ->with(0)
        ->willReturn(['size' => 1]);
    $zipArchive->expects($this->once())
        ->method('getNameIndex')
        ->with(0)
        ->willReturn($filename);

    Log::shouldReceive('debug')
        ->once()
        ->with('no factory found', ['factoryName' => $filename]);
    Log::shouldReceive('info')
        ->times(4);

    $zipImporter = new ZipImporter($zipArchive, [], [], 1, 1);
    $zipImporter->importFiles([$importFile], fake()->uuid(), $organisation->id);
});

it('skips the import if importer cannot be found', function (): void {
    $organisation = Organisation::factory()->create();
    $factoryName = fake()->slug();
    $fileExtension = fake()->fileExtension();
    $filename = sprintf('%s/%s.%s', $factoryName, fake()->slug(), $fileExtension);
    $importFile = new TemporaryUploadedFile('import.zip', '');

    $zipArchive = $this->createMock(ZipArchive::class);
    $zipArchive->expects($this->once())
        ->method('count')
        ->willReturn(1);
    $zipArchive->expects($this->once())
        ->method('statIndex')
        ->with(0)
        ->willReturn(['size' => 1]);
    $zipArchive->expects($this->once())
        ->method('getNameIndex')
        ->with(0)
        ->willReturn($filename);

    Log::shouldReceive('debug')
        ->once()
        ->with('no importer found', ['extension' => $fileExtension]);
    Log::shouldReceive('info')
        ->times(5);

    $zipImporter = new ZipImporter($zipArchive, [$factoryName => fake()->slug()], [], 1, 1);
    $zipImporter->importFiles([$importFile], fake()->uuid(), $organisation->id);
});
