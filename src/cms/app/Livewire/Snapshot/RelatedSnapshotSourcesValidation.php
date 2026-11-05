<?php

declare(strict_types=1);

namespace App\Livewire\Snapshot;

use App\Filament\Resources\RelatedSnapshotSourceResource;
use App\Models\Snapshot;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

use function __;
use function view;

class RelatedSnapshotSourcesValidation extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Snapshot $snapshot;

    public function table(Table $table): Table
    {
        return RelatedSnapshotSourceResource::table($table)
            ->query($this->snapshot->relatedSnapshotSourcesWithoutEstablishedSnapshot()->getQuery())
            ->emptyStateIcon('heroicon-o-check')
            ->emptyStateHeading(__('snapshot_transition.establish.related_snapshot_sources_approved'));
    }

    public function render(): View
    {
        return view('livewire.filament.table');
    }
}
