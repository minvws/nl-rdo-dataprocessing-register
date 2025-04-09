<?php

declare(strict_types=1);

use App\Components\Uuid\UuidInterface;
use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\FeatureTestCase;
use Tests\Helpers\ConfigHelper;
use Webmozart\Assert\Assert;

uses(FeatureTestCase::class)
    ->in('Feature');

uses()
    ->beforeEach(function (): void {
        $this->organisation = Organisation::factory()->create();
        $this->user = User::factory()
            ->hasAttached($this->organisation)
            ->withValidOtpRegistration()
            ->create();

        $this->user->assignOrganisationRole(Role::PRIVACY_OFFICER, $this->organisation);

        $this->be($this->user);
        Filament::setTenant($this->organisation);

        Storage::fake('tmp-for-tests');
        Storage::fake(ConfigHelper::get('public-website.hugo_filesystem_disk'));

        setOtpValidSessionValue(true);
    })
    ->in('Feature/Filament', 'Feature/Livewire');

function prepareImportZip(string $filename): string
{
    $path = base_path('tests/.pest/files/import.zip');
    assert(File::exists($path));

    /** @var FilesystemAdapter $storage */
    $storage = Storage::disk(ConfigHelper::get('filesystems.default'));

    $storage->put(sprintf('livewire-tmp/%s', $filename), File::get($path));

    return $path;
}

function copyFileToDisk(string $originalFilePath, string $destinationFilePath, string $disk): FilesystemAdapter
{
    assert(File::exists($originalFilePath));

    /** @var FilesystemAdapter $storage */
    $storage = Storage::fake($disk);

    $storage->put($destinationFilePath, File::get($originalFilePath));

    return $storage;
}

function setOtpValidSessionValue(bool $value): void
{
    Session::put('otp_valid', $value);
}

expect()->intercept('toBe', UuidInterface::class, function (UuidInterface $expected): void {
    Assert::isInstanceOf($this->value, UuidInterface::class);

    expect($this->value->equals($expected))
        ->toBeTrue();
});
