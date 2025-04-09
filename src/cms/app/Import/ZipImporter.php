<?php

declare(strict_types=1);

namespace App\Import;

use App\Components\Uuid\Uuid;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Webmozart\Assert\Assert;
use ZipArchive;

use function array_key_exists;
use function count;
use function sprintf;
use function sys_get_temp_dir;

use const DIRECTORY_SEPARATOR;

class ZipImporter
{
    private const BYTES_IN_A_MEGABYTE = 1_024 * 1_024;

    /**
     * @param array<class-string<Factory>> $factories
     * @param array<class-string<Importer>> $importers
     */
    public function __construct(
        private readonly ZipArchive $zipArchive,
        private readonly array $factories,
        private readonly array $importers,
        private readonly int $maxZippedFilesize,
        private readonly int $maxZippedNumberOfFiles,
    ) {
    }

    /**
     * @param array<TemporaryUploadedFile> $files
     *
     * @throws FileNotFoundException
     * @throws ImportFailedException
     */
    public function importFiles(array $files, string $userId, string $organisationId): void
    {
        Log::info('import files', ['file_count' => count($files)]);

        foreach ($files as $file) {
            $this->import($file->getRealPath(), $userId, $organisationId);
        }
    }

    /**
     * @throws FileNotFoundException
     * @throws ImportFailedException
     */
    private function import(string $zipPath, string $userId, string $organisationId): void
    {
        Log::info('starting import of zip-file', ['filepath' => $zipPath]);

        $this->zipArchive->open($zipPath);

        $numberOfFilesInZip = $this->zipArchive->count();
        Log::info('number of files in zip', ['numberOfFilesInZip' => $numberOfFilesInZip]);

        if ($numberOfFilesInZip > $this->maxZippedNumberOfFiles) {
            throw new ImportFailedException('too many files in zip');
        }

        $tempDir = sys_get_temp_dir();

        for ($i = 0; $i < $numberOfFilesInZip; $i++) {
            $this->assertMaxZippedFilesize($this->zipArchive, $i);

            $zipFilename = $this->zipArchive->getNameIndex($i);
            Assert::string($zipFilename);

            $fileExtension = File::extension($zipFilename);
            $tempfileName = sprintf('%s.%s', Uuid::generate()->toString(), $fileExtension);

            $tempfilePath = sprintf('%s/%s', $tempDir, $tempfileName);

            Log::info('renaming & extracting zip-file', [
                'zipFilename' => $zipFilename,
                'tempfilePath' => $tempfilePath,
            ]);

            try {
                $this->zipArchive->renameName($zipFilename, $tempfileName);
                $this->zipArchive->extractTo($tempDir, $tempfileName);

                $factoryClass = $this->getFactoryClass($zipFilename);
                if ($factoryClass === null) {
                    continue;
                }

                $importer = $this->getImporter($fileExtension);
                if ($importer === null) {
                    continue;
                }

                $fileContents = File::get($tempfilePath);
                $importer->import($zipFilename, $fileContents, $factoryClass, $userId, $organisationId);
            } finally {
                File::delete($tempfilePath);
            }
        }
    }

    /**
     * @return ?class-string<Factory>
     */
    private function getFactoryClass(string $filePath): ?string
    {
        $filePathParts = Str::of($filePath)->explode(DIRECTORY_SEPARATOR);
        $factoryName = $filePathParts->offsetGet(0);
        Assert::string($factoryName);

        if (!array_key_exists($factoryName, $this->factories)) {
            Log::debug('no factory found', ['factoryName' => $factoryName]);
            return null;
        }

        Log::info('factory class found', ['factoryName' => $factoryName]);

        return $this->factories[$factoryName];
    }

    private function getImporter(string $extension): ?Importer
    {
        if (!array_key_exists($extension, $this->importers)) {
            Log::debug('no importer found', ['extension' => $extension]);
            return null;
        }

        $importer = App::make($this->importers[$extension]);
        Assert::isInstanceOf($importer, Importer::class);

        Log::info('importer class found', ['importer' => $importer]);

        return $importer;
    }

    private function getMaxZippedFilesizeInBytes(): int
    {
        return $this->maxZippedFilesize * self::BYTES_IN_A_MEGABYTE;
    }

    /**
     * @throws ImportFailedException
     */
    private function assertMaxZippedFilesize(ZipArchive $zipArchive, int $i): void
    {
        $statIndex = $zipArchive->statIndex($i);
        Assert::isArray($statIndex);
        Assert::keyExists($statIndex, 'size');

        $size = $statIndex['size'];
        Assert::integer($size);

        if ($size > $this->getMaxZippedFilesizeInBytes()) {
            throw new ImportFailedException('filesize too large in zip');
        }
    }
}
