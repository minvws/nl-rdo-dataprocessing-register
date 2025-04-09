<?php

declare(strict_types=1);

namespace App\Livewire\Snapshot;

use App\Models\Snapshot;
use Illuminate\Contracts\View\View;
use Livewire\Component;

use function view;

class ApprovalLogs extends Component
{
    public Snapshot $snapshot;

    public function render(): View
    {
        return view('livewire.snapshot.approval_logs');
    }
}
