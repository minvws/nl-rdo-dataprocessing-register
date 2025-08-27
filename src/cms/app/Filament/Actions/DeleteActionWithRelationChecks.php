<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Throwable;

use function __;

class DeleteActionWithRelationChecks extends DeleteAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (array $data, Model $record): void {
            try {
                $record->delete();
            } catch (Throwable) {
                Notification::make()
                    ->danger()
                    ->title(__('general.error'))
                    ->body(__('error.delete_abort_constraints_not_empty'))
                    ->send();

                $this->halt();
            }

            $this->success();
        });
    }
}
