<?php

declare(strict_types=1);

namespace App\Filament\Actions\User;

use App\Facades\AuditLog;
use App\Models\User;
use App\Services\AuditLog\User\UserTwoFactorResetEvent;
use App\Services\OtpService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

use function __;

class OtpDisableAction extends Action
{
    public static function make(?string $name = 'otp_disable'): static
    {
        return parent::make($name)
            ->label(__('user.one_time_password.disable'))
            ->requiresConfirmation()
            ->action(static function (User $record, OtpService $otpService): void {
                $otpService->disable($record);

                AuditLog::register(new UserTwoFactorResetEvent($record));

                Notification::make()
                    ->title(__('user.one_time_password.disabled'))
                    ->success()
                    ->send();
            });
    }
}
