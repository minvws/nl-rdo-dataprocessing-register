<?php

declare(strict_types=1);

namespace App\Services\FormDraft;

use App\Models\FormDraft;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class FormDraftService
{
    public function find(User $user, Organisation $organisation, string $resourceClass, string $draftKey): ?FormDraft
    {
        return $this->query($user, $organisation, $resourceClass)
            ->where('draft_key', $draftKey)
            ->first();
    }

    public function findLatestWithoutRecord(User $user, Organisation $organisation, string $resourceClass): ?FormDraft
    {
        return $this->query($user, $organisation, $resourceClass)
            ->whereNull('record_id')
            ->latest('updated_at')
            ->first();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function save(
        User $user,
        Organisation $organisation,
        string $resourceClass,
        ?string $recordId,
        string $draftKey,
        array $data,
    ): FormDraft {
        $formDraft = $this->find($user, $organisation, $resourceClass, $draftKey);

        if ($formDraft !== null && $formDraft->payload === $data) {
            return $formDraft;
        }

        $formDraft = FormDraft::updateOrCreate([
            'user_id' => $user->id->toString(),
            'resource_class' => $resourceClass,
            'draft_key' => $draftKey,
        ], [
            'organisation_id' => $organisation->id->toString(),
            'record_id' => $recordId,
            'payload' => $data,
        ]);

        if ($recordId === null) {
            $this->deleteOtherWithoutRecord($user, $organisation, $resourceClass, $draftKey);
        }

        return $formDraft;
    }

    public function delete(User $user, Organisation $organisation, string $resourceClass, string $draftKey): void
    {
        $this->query($user, $organisation, $resourceClass)
            ->where('draft_key', $draftKey)
            ->delete();
    }

    private function deleteOtherWithoutRecord(
        User $user,
        Organisation $organisation,
        string $resourceClass,
        string $draftKey,
    ): void {
        $this->query($user, $organisation, $resourceClass)
            ->whereNull('record_id')
            ->where('draft_key', '!=', $draftKey)
            ->delete();
    }

    /**
     * @return Builder<FormDraft>
     */
    private function query(User $user, Organisation $organisation, string $resourceClass): Builder
    {
        return FormDraft::query()
            ->where('user_id', $user->id->toString())
            ->where('organisation_id', $organisation->id->toString())
            ->where('resource_class', $resourceClass);
    }
}
