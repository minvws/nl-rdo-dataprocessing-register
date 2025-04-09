<?php

declare(strict_types=1);

use App\Import\Importers\JsonImporter;
use Illuminate\Support\Facades\Log;

it('can handle empty json', function (): void {
    $factoryClass = fake()->word();

    Log::shouldReceive('info')
        ->once()
        ->with('starting input of json-data');
    Log::shouldReceive('info')
        ->once()
        ->with('json-dataset is empty, skipping import', ['factoryClass' => $factoryClass]);

    $jsonImporter = new JsonImporter();
    $jsonImporter->import(fake()->word(), '{}', $factoryClass, fake()->uuid(), fake()->uuid());
});

it('fails on invalid json', function (): void {
    $jsonImporter = new JsonImporter();
    $jsonImporter->import(fake()->word(), fake()->sentence(), fake()->word(), fake()->uuid(), fake()->uuid());
})->expectExceptionMessage('invalid json');
