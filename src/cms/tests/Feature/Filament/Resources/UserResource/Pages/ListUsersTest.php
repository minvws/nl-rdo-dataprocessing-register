<?php

declare(strict_types=1);

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Actions\UserImportAction;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tests\Helpers\ConfigTestHelper;

it('loads the list page', function (): void {
    $this->asFilamentUser()
        ->get(UserResource::getUrl())
        ->assertSuccessful();
});

it('imports csv with users', function (): void {
    Storage::fake(ConfigTestHelper::get('filesystems.default'));

    $email1 = fake()->safeEmail();
    $email2 = fake()->safeEmail();

    $this->assertDatabaseMissing('users', ['email' => $email1]);
    $this->assertDatabaseMissing('users', ['email' => $email2]);

    $csvContents = [
        'name,email',
        sprintf('%s,%s', fake()->name(), $email1),
        sprintf('%s,%s', fake()->name(), $email2),
    ];

    $this->asFilamentUser()
        ->createLivewireTestable(ListUsers::class)
        ->callAction(UserImportAction::class, [
            'file' => createUserImportCsvFile($csvContents),
        ]);

    $this->assertDatabaseHas(User::class, ['email' => $email1]);
    $this->assertDatabaseHas(User::class, ['email' => $email2]);
});

it('lowercases the email address on import', function (): void {
    Storage::fake(ConfigTestHelper::get('filesystems.default'));

    $email = 'Foo@Bar.com';

    $this->assertDatabaseMissing('users', ['email' => 'Foo@Bar.com']);
    $this->assertDatabaseMissing('users', ['email' => 'foo@bar.com']);

    $csvContents = ['name,email', sprintf('%s,%s', fake()->name(), $email)];

    $this->asFilamentUser()
        ->createLivewireTestable(ListUsers::class)
        ->callAction(UserImportAction::class, [
            'file' => createUserImportCsvFile($csvContents),
        ]);

    $this->assertDatabaseMissing('users', ['email' => 'Foo@Bar.com']);
    $this->assertDatabaseHas(User::class, ['email' => 'foo@bar.com']);
});

it('skips non-unique users on import', function (): void {
    Storage::fake(ConfigTestHelper::get('filesystems.default'));

    User::factory()->create([
        'email' => 'foo@bar.com',
    ]);

    $this->assertDatabaseHas(User::class, ['email' => 'foo@bar.com']);
    $this->assertDatabaseMissing('users', ['email' => 'bar@foo.com']);

    $csvContents = [
        'name,email',
        sprintf('%s,%s', fake()->name(), 'foo@bar.com'),
        sprintf('%s,%s', fake()->name(), 'bar@foo.com'),
    ];

    $this->asFilamentUser()
        ->createLivewireTestable(ListUsers::class)
        ->callAction(UserImportAction::class, [
            'file' => createUserImportCsvFile($csvContents),
        ]);

    $this->assertDatabaseHas(User::class, ['email' => 'foo@bar.com']);
    $this->assertDatabaseHas(User::class, ['email' => 'bar@foo.com']);
});

/**
 * @param array<int, string> $contents
 */
function createUserImportCsvFile(array $contents = []): File
{
    return TemporaryUploadedFile::fake()->createWithContent('file.csv', collect($contents)->join("\n"));
}
