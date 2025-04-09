<?php

declare(strict_types=1);

namespace App\Filament\Actions\OneTimePassword;

use App\Facades\Otp;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

use function __;

class RegenerateCodesAction extends Action
{
    public static function makeForUser(User $user): static
    {
        return parent::make('regenerateCodes')
            ->label(__('user.profile.one_time_password.regenerate_codes.action'))
            ->requiresConfirmation()
            ->action(static function () use ($user): void {
                Otp::disable($user);
                Otp::enable($user);

                Notification::make()
                    ->success()
                    ->title(__('user.profile.one_time_password.regenerate_codes.notify'))
                    ->send();
            });
    }
}
