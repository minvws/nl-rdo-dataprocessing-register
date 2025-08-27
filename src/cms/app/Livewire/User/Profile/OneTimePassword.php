<?php

declare(strict_types=1);

namespace App\Livewire\User\Profile;

use App\Config\Config;
use App\Facades\Authentication;
use App\Facades\Otp;
use App\Filament\Actions\OneTimePassword\ConfirmAction;
use App\Filament\Actions\OneTimePassword\DisableAction;
use App\Filament\Actions\OneTimePassword\EnableAction;
use App\Filament\Actions\OneTimePassword\RegenerateCodesAction;
use App\Models\User;
use App\ValueObjects\OneTimePassword\Secret;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Webmozart\Assert\Assert;

use function sprintf;
use function view;

class OneTimePassword extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public User $user;
    public string $code;

    public function mount(): void
    {
        $this->user = Authentication::user();
    }

    public function render(): View
    {
        return view('livewire.user.profile.one-time-password');
    }

    public function enableAction(): Action
    {
        return EnableAction::makeForUser($this->user);
    }

    public function disableAction(): Action
    {
        return DisableAction::makeForUser($this->user);
    }

    public function confirmAction(): Action
    {
        return ConfirmAction::makeForUser($this->user);
    }

    public function regenerateCodesAction(): Action
    {
        return RegenerateCodesAction::makeForUser($this->user);
    }

    public function getQrCode(): string
    {
        $otpSecret = $this->user->otp_secret;
        Assert::string($otpSecret);

        return Otp::generateQRCodeInline(
            Secret::fromString($otpSecret),
            sprintf('%s (%s)', Config::string('app.name'), $this->user->email),
        );
    }
}
