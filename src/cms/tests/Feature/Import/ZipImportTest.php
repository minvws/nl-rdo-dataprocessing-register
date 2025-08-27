<?php

declare(strict_types=1);

use App\Components\Uuid\Uuid;
use App\Import\ImportFailedException;
use App\Import\ZipImporter;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Organisation;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

it('can process a zip file', function (): void {
    $importIdThatExistsInTheTestZipFile = '12536';
    $numberThatExistsInTheTestZipFile = 'M12536';

    $zipFilename = sprintf('%s.zip', fake()->slug);
    prepareImportZip($zipFilename);

    $organisation = Organisation::factory()
        ->create();
    $importFile = new TemporaryUploadedFile($zipFilename, '');

    $zipImporter = $this->app->get(ZipImporter::class);
    $zipImporter->importFiles([$importFile], Uuid::fromString(fake()->uuid()), $organisation->id->toString());

    $avgProcessorProcessingRecord = AvgProcessorProcessingRecord::query()
        ->where('import_id', $importIdThatExistsInTheTestZipFile)
        ->firstOrFail();
    expect($avgProcessorProcessingRecord->import_number)
        ->toBe($numberThatExistsInTheTestZipFile);
});

it('fails if there are too many files in the zip', function (): void {
    $organisation = Organisation::factory()->create();
    $importFile = new TemporaryUploadedFile('import.zip', '');

    $zipArchive = $this->mock(ZipArchive::class)
        ->shouldReceive('open')
        ->once()
        ->shouldReceive('count')
        ->once()
        ->andReturn(2)
        ->getMock();

    $zipImporter = new ZipImporter($zipArchive, [], [], 1, 1);

    $this->expectException(ImportFailedException::class);
    $this->expectExceptionMessage('too many files in zip');
    $zipImporter->importFiles([$importFile], Uuid::fromString(fake()->uuid()), $organisation->id->toString());
});

it('fails if the filesize in the zip is too large', function (): void {
    $importFile = new TemporaryUploadedFile('import.zip', '');
    $organisation = Organisation::factory()->create();

    $zipArchive = $this->mock(ZipArchive::class)
        ->shouldReceive('open')
        ->once()
        ->shouldReceive('count')
        ->once()
        ->andReturn(1)
        ->shouldReceive('statIndex')
        ->once()
        ->with(0)
        ->andReturn(['size' => (1_024 * 1_024) + 1])
        ->getMock();

    $zipImporter = new ZipImporter($zipArchive, [], [], 1, 1);

    $this->expectException(ImportFailedException::class);
    $this->expectExceptionMessage('filesize too large in zip');
    $zipImporter->importFiles([$importFile], Uuid::fromString(fake()->uuid()), $organisation->id->toString());
});

it('skips the import if factory cannot be found', function (): void {
    $organisation = Organisation::factory()->create();
    $filename = sprintf('%s.json', fake()->slug());
    $importFile = new TemporaryUploadedFile('import.zip', '');

    $zipArchive = $this->mock(ZipArchive::class)
        ->shouldReceive('open')
        ->shouldReceive('count')
        ->once()
        ->andReturn(1)
        ->shouldReceive('statIndex')
        ->once()
        ->with(0)
        ->andReturn(['size' => 1])
        ->shouldReceive('getNameIndex')
        ->once()
        ->with(0)
        ->andReturn($filename)
        ->shouldReceive('renameName')
        ->once()
        ->shouldReceive('extractTo')
        ->once()
        ->getMock();

    Log::shouldReceive('debug')
        ->once()
        ->with('no factory found', ['factoryName' => $filename]);
    Log::shouldReceive('info')
        ->times(4);

    $zipImporter = new ZipImporter($zipArchive, [], [], 1, 1);
    $zipImporter->importFiles([$importFile], Uuid::fromString(fake()->uuid()), $organisation->id->toString());
});

it('skips the import if importer cannot be found', function (): void {
    $organisation = Organisation::factory()->create();
    $factoryName = fake()->slug();
    $fileExtension = fake()->fileExtension();
    $filename = sprintf('%s/%s.%s', $factoryName, fake()->slug(), $fileExtension);
    $importFile = new TemporaryUploadedFile('import.zip', '');

    $zipArchive = $this->mock(ZipArchive::class)
        ->shouldReceive('open')
        ->once()
        ->shouldReceive('count')
        ->once()
        ->andReturn(1)
        ->shouldReceive('statIndex')
        ->once()
        ->with(0)
        ->andReturn(['size' => 1])
        ->shouldReceive('getNameIndex')
        ->once()
        ->with(0)
        ->andReturn($filename)
        ->shouldReceive('renameName')
        ->once()
        ->shouldReceive('extractTo')
        ->once()
        ->getMock();

    Log::shouldReceive('debug')
        ->once()
        ->with('no importer found', ['extension' => $fileExtension]);
    Log::shouldReceive('info')
        ->times(5);

    $zipImporter = new ZipImporter($zipArchive, [$factoryName => fake()->slug()], [], 1, 1);
    $zipImporter->importFiles([$importFile], Uuid::fromString(fake()->uuid()), $organisation->id->toString());
});
