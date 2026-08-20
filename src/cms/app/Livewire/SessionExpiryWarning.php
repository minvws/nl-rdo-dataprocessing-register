<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Config\Config;
use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Livewire\Component;

use function view;

class SessionExpiryWarning extends Component
{
    public const string EXPIRED_QUERY_PARAMETER = 'expired';
    private const int WARN_MINUTES_BEFORE_EXPIRY = 15;

    public function render(): View
    {
        return view('livewire.session-expiry-warning', [
            'lifetimeInSeconds' => Config::integer('session.lifetime') * 60,
            'warnSecondsBeforeExpiry' => self::WARN_MINUTES_BEFORE_EXPIRY * 60,
            'expiredLoginUrl' => Filament::getLoginUrl([self::EXPIRED_QUERY_PARAMETER => 1]),
        ]);
    }
}
