<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages\Concerns;

use App\Components\Uuid\Uuid;
use App\Components\Uuid\UuidInterface;
use App\Config\Config;
use App\Facades\Authentication;
use App\Models\FormDraft;
use App\Services\DateFormatService;
use App\Services\FormDraft\FormDraftService;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Webmozart\Assert\Assert;

use function __;
use function array_replace;
use function is_array;
use function is_int;

trait HasDraftAutosave
{
    private const string RESTORE_SESSION_KEY = 'form_draft_restore';

    #[Locked]
    public ?string $draftKey = null;

    #[Locked]
    public ?string $restorableDraftKey = null;

    #[Locked]
    public ?string $restorableDraftSavedAt = null;

    #[Locked]
    public ?string $draftLastSavedAt = null;

    final public function mountHasDraftAutosave(): void
    {
        if (!Config::boolean('autosave.enabled')) {
            return;
        }

        $recordId = $this->draftRecordId();
        $draftKey = $recordId ?? Uuid::generate()->toString();

        $this->draftKey = $draftKey;

        $formDraft = $this->findRestorableDraft($recordId, $draftKey);

        if ($formDraft === null) {
            return;
        }

        if ($this->pullRestoreFlag($formDraft)) {
            $this->applyDraft($formDraft);

            return;
        }

        $this->restorableDraftKey = $formDraft->draft_key->toString();
        $this->restorableDraftSavedAt = $formDraft->updated_at->diffForHumans();
    }

    final public function saveDraft(): ?string
    {
        if (!Config::boolean('autosave.enabled') || $this->draftKey === null || !is_array($this->data)) {
            return null;
        }

        if ($this->restorableDraftKey !== null) {
            return null;
        }

        $formDraft = $this->formDraftService()->save(
            Authentication::user(),
            Authentication::organisation(),
            static::getResource(),
            $this->draftRecordId(),
            $this->draftKey,
            $this->data,
        );

        $this->draftLastSavedAt = DateFormatService::toTime($formDraft->updated_at);

        return $this->draftLastSavedAt;
    }

    final public function restoreDraft(): void
    {
        if ($this->restorableDraftKey === null) {
            return;
        }

        $formDraft = $this->formDraftService()->find(
            Authentication::user(),
            Authentication::organisation(),
            static::getResource(),
            $this->restorableDraftKey,
        );

        if ($formDraft === null) {
            return;
        }

        Session::put(self::RESTORE_SESSION_KEY, [
            'form_draft_id' => $formDraft->id->toString(),
            'expires_at' => CarbonImmutable::now()->addMinute()->getTimestamp(),
        ]);

        $this->skipRender();
    }

    final public function ignoreDraft(): void
    {
        $this->restorableDraftKey = null;
        $this->restorableDraftSavedAt = null;
    }

    final public function confirmDraftOverwriteAction(): Action
    {
        return Action::make('confirmDraftOverwrite')
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-exclamation-triangle')
            ->modalHeading(__('draft.overwrite_heading'))
            ->modalDescription(fn (): string => __('draft.overwrite_description', [
                'saved_at' => $this->restorableDraftSavedAt ?? '',
            ]))
            ->modalSubmitActionLabel(__('draft.overwrite_confirm'))
            ->modalCancelActionLabel(__('draft.overwrite_cancel'))
            ->action(function (): void {
                $this->restorableDraftKey = null;
                $this->restorableDraftSavedAt = null;

                $this->renderIsland('draft-autosave-banner');
                $this->dispatch('draft-overwrite-confirmed');
            });
    }

    final protected function afterSave(): void
    {
        $this->deleteDraft();
    }

    final protected function afterCreate(): void
    {
        $this->deleteDraft();
    }

    final protected function draftRecordId(): ?string
    {
        if (!$this instanceof EditRecord) {
            return null;
        }

        $record = $this->record;
        Assert::isInstanceOf($record, Model::class);

        $key = $record->getKey();
        Assert::isInstanceOf($key, UuidInterface::class);

        return $key->toString();
    }

    private function findRestorableDraft(?string $recordId, string $draftKey): ?FormDraft
    {
        if ($recordId !== null) {
            return $this->formDraftService()->find(
                Authentication::user(),
                Authentication::organisation(),
                static::getResource(),
                $draftKey,
            );
        }

        return $this->formDraftService()->findLatestWithoutRecord(
            Authentication::user(),
            Authentication::organisation(),
            static::getResource(),
        );
    }

    private function pullRestoreFlag(FormDraft $formDraft): bool
    {
        $flag = Session::pull(self::RESTORE_SESSION_KEY);

        if (!is_array($flag)) {
            return false;
        }

        $expiresAt = $flag['expires_at'] ?? null;

        if (!is_int($expiresAt) || $expiresAt < CarbonImmutable::now()->getTimestamp()) {
            return false;
        }

        return ($flag['form_draft_id'] ?? null) === $formDraft->id->toString();
    }

    private function applyDraft(FormDraft $formDraft): void
    {
        $this->data = array_replace($this->data ?? [], $formDraft->payload);
        $this->draftKey = $formDraft->draft_key->toString();
        $this->draftLastSavedAt = DateFormatService::toTime($formDraft->updated_at);

        Notification::make()
            ->title(__('draft.restored'))
            ->send();
    }

    private function deleteDraft(): void
    {
        if ($this->draftKey === null) {
            return;
        }

        $this->formDraftService()->delete(
            Authentication::user(),
            Authentication::organisation(),
            static::getResource(),
            $this->draftKey,
        );
    }

    private function formDraftService(): FormDraftService
    {
        /** @var FormDraftService $formDraftService */
        $formDraftService = App::get(FormDraftService::class);

        return $formDraftService;
    }
}
