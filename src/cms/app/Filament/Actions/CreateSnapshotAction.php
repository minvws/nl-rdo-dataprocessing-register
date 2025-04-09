<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;
use App\Filament\RelationManagers\SnapshotsRelationManager;
use App\Models\Contracts\SnapshotSource;
use App\Services\Snapshot\SnapshotFactory;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Webmozart\Assert\Assert;

use function __;
use function json_encode;
use function md5;
use function str;

use const JSON_UNESCAPED_UNICODE;

class CreateSnapshotAction extends Action
{
    public static function make(?string $name = 'snapshot_create'): static
    {
        return parent::make($name)
            ->label(__('snapshot.create'))
            ->visible(Authorization::hasPermission(Permission::SNAPSHOT_CREATE))
            ->requiresConfirmation()
            ->action(static function (Model $record, SnapshotFactory $snapshotFactory): void {
                Assert::isInstanceOf($record, SnapshotSource::class);
                $snapshotFactory->fromSnapshotSource($record);

                Notification::make()
                    ->title(__('snapshot.created'))
                    ->success()
                    ->send();
            })
            ->after(static function (Component $livewire): void {
                $livewire->dispatch(SnapshotsRelationManager::REFRESH_TABLE_EVENT);
            });
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function makeWithChangesCheck(?array $data, string $savedDataHash, string $name = 'snapshot_create'): static
    {
        return self::make($name)
            ->action(
                static function (
                    CreateSnapshotAction $action,
                    Model $record,
                    SnapshotFactory $snapshotFactory,
                ) use (
                    $data,
                    $savedDataHash,
                ): void {
                    $dataHash = self::createDataHash($data);

                    if ($dataHash !== $savedDataHash) {
                        Notification::make()
                            ->title(__('snapshot.unsaved_changes'))
                            ->danger()
                            ->send();

                        $action->halt();
                    }

                    Assert::isInstanceOf($record, SnapshotSource::class);
                    $snapshotFactory->fromSnapshotSource($record);

                    Notification::make()
                        ->title(__('snapshot.created'))
                        ->success()
                        ->send();
                },
            );
    }

    /**
     * @param array<string, mixed> $data
     *
     * To compare the hash, we need to rebuild hash from the current state (data). Filament does not provide a method for creating a hash,
     * so we have to copy the functionality here. See \Filament\Pages\Concerns\HasUnsavedDataChangesAlert
     */
    private static function createDataHash(?array $data): string
    {
        $jsonEncodedString = json_encode($data, JSON_UNESCAPED_UNICODE);
        Assert::string($jsonEncodedString);

        /** @SuppressWarnings("php:S4790") hash algorithm is not used in a sensitive context here */
        return md5((string) str($jsonEncodedString)->replace('\\', ''));
    }
}
