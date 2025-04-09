<?php

declare(strict_types=1);

namespace App\Import\Importers;

use App\Import\Factory;
use App\Import\Importer;
use App\Import\ImportFailedException;
use App\Jobs\ImportEntityJob;
use App\Jobs\ImportFinishedJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function count;
use function json_decode;
use function str_replace;

class JsonImporter implements Importer
{
    /**
     * @param class-string<Factory> $factoryClass
     *
     * @throws ImportFailedException
     */
    public function import(string $zipFilename, string $input, string $factoryClass, string $userId, string $organisationId): void
    {
        Log::info('starting input of json-data');

        $input = $this->removeUtf8ByteOrderMark($input);
        $dataSets = $this->getDataFromJson($input);

        $dataSetCount = count($dataSets);
        if ($dataSetCount === 0) {
            Log::info('json-dataset is empty, skipping import', ['factoryClass' => $factoryClass]);
            return;
        }

        Log::info('start dispatching jobs', ['dataSetCount' => $dataSetCount, 'factoryClass' => $factoryClass]);
        foreach ($dataSets as $dataSet) {
            Assert::isArray($dataSet);

            ImportEntityJob::dispatch($factoryClass, $dataSet, $organisationId);
            Log::info('dispatched job to import data', ['factoryClass' => $factoryClass, 'organisationId' => $organisationId]);
        }

        Log::info('finished dispatching jobs', ['factoryClass' => $factoryClass]);
        ImportFinishedJob::dispatch($zipFilename, $userId);
    }

    private function removeUtf8ByteOrderMark(string $input): string
    {
        $input = str_replace("\xEF\xBB\xBF", '', $input);
        Assert::string($input);

        return $input;
    }

    /**
     * @throws ImportFailedException
     */
    private function getDataFromJson(string $input): array
    {
        if (!Str::of($input)->isJson()) {
            throw new ImportFailedException('invalid json');
        }

        $jsonData = json_decode($input, true);
        Assert::isArray($jsonData);

        return $jsonData;
    }
}
