<?php

declare(strict_types=1);

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\Helpers\ConfigTestHelper;

function copyFileToDisk(string $originalFilePath, string $destinationFilePath, string $disk): FilesystemAdapter
{
    assert(File::exists($originalFilePath));

    /** @var FilesystemAdapter $storage */
    $storage = Storage::fake($disk);
    $storage->put($destinationFilePath, File::get($originalFilePath));

    return $storage;
}

/**
 * Since all http calls are faked in the setUp(), we need to "reset" the facade before faking a specific http-call
 *
 * @param callable|array<string, mixed> $callback
 */
function httpFake(callable|array $callback): void
{
    App::forgetInstance(get_class(Http::getFacadeRoot()));
    Http::clearResolvedInstances();

    Http::fake($callback);
}

/**
 * @param array<string> $parameters
 */
function mockRequest(string $uri, array $parameters = []): Request
{
    $route = new Route('get', $uri, ['as' => $uri]);
    $route->parameters = $parameters;

    return Mockery::mock(Request::class)
        ->shouldReceive('route')
        ->once()
        ->andReturn($route)
        ->getMock();
}

function prepareImportZip(string $filename): string
{
    $path = base_path('tests/.pest/files/import.zip');
    assert(File::exists($path));

    /** @var FilesystemAdapter $storage */
    $storage = Storage::disk(ConfigTestHelper::get('filesystems.default'));
    $storage->put(sprintf('livewire-tmp/%s', $filename), File::get($path));

    return $path;
}
