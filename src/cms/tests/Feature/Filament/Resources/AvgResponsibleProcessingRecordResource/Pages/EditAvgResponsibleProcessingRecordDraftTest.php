<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\EditAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgGoal;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\FormDraft;
use Tests\Helpers\ConfigTestHelper;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('saves a draft of the form state', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->fillForm(['name' => 'Drafted name'])
        ->call('saveDraft');

    $formDraft = FormDraft::all()->sole();

    expect($formDraft->resource_class)
        ->toBe(AvgResponsibleProcessingRecordResource::class)
        ->and($formDraft->record_id?->toString())
        ->toBe($avgResponsibleProcessingRecord->id->toString())
        ->and($formDraft->draft_key->toString())
        ->toBe($avgResponsibleProcessingRecord->id->toString())
        ->and($formDraft->payload['name'])
        ->toBe('Drafted name');
});

it('updates the same draft and skips identical form states', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    $testable = $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->fillForm(['name' => 'First draft'])
        ->call('saveDraft');

    $formDraft = FormDraft::all()->sole();
    $firstSavedAt = $formDraft->updated_at;

    $this->travel(5)->minutes();

    $testable->call('saveDraft');

    expect($formDraft->refresh()->updated_at?->toIso8601String())
        ->toBe($firstSavedAt?->toIso8601String());

    $testable->fillForm(['name' => 'Second draft'])
        ->call('saveDraft');

    $formDraft = FormDraft::all()->sole();

    expect($formDraft->payload['name'])
        ->toBe('Second draft')
        ->and($formDraft->updated_at?->toIso8601String())
        ->not->toBe($firstSavedAt?->toIso8601String());
});

it('does not touch a draft of another user', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $otherUserFormDraft = FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => UserTestHelper::createForOrganisation($organisation)->id->toString(),
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
            'payload' => ['name' => 'Draft of another user'],
        ]);

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->fillForm(['name' => 'My own draft'])
        ->call('saveDraft');

    expect(FormDraft::all())
        ->toHaveCount(2)
        ->and($otherUserFormDraft->refresh()->payload['name'])
        ->toBe('Draft of another user');
});

it('deletes the draft when the record is saved', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->fillForm(['name' => 'Drafted name'])
        ->call('saveDraft')
        ->call('save')
        ->assertHasNoFormErrors();

    expect(FormDraft::all())
        ->toHaveCount(0);
});

it('keeps the draft when the offer is ignored', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->assertSet('restorableDraftKey', $avgResponsibleProcessingRecord->id->toString())
        ->call('ignoreDraft')
        ->assertSet('restorableDraftKey', null)
        ->assertSet('restorableDraftSavedAt', null);

    expect(FormDraft::all())
        ->toHaveCount(1);
});

it('does not delete create drafts of the same resource', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $createFormDraft = FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'payload' => ['name' => 'Draft without a record'],
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->fillForm(['name' => 'Drafted name'])
        ->call('saveDraft');

    expect(FormDraft::all())
        ->toHaveCount(2)
        ->and($createFormDraft->refresh()->payload['name'])
        ->toBe('Draft without a record');
});

it('offers a restorable draft on mount', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->assertSet('restorableDraftKey', $avgResponsibleProcessingRecord->id->toString())
        ->assertSet('restorableDraftSavedAt', fn (?string $savedAt): bool => $savedAt !== null);
});

it('does not offer drafts of another user, another record or another organisation', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $otherRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();

    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
        ]);
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'record_id' => $otherRecord->id->toString(),
            'draft_key' => $otherRecord->id->toString(),
        ]);
    FormDraft::factory()
        ->create([
            'user_id' => $user->id->toString(),
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->assertSet('restorableDraftSavedAt', null);
});

it('restores a draft and keeps the repeater record keys', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    $avgGoal = AvgGoal::factory()
        ->recycle($organisation)
        ->create([
            'goal' => 'Original goal',
            'remarks' => 'Original remarks',
        ]);
    $avgResponsibleProcessingRecord->avgGoals()->attach($avgGoal);

    $recordKey = 'record-' . $avgGoal->id->toString();
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
            'payload' => [
                'name' => 'Drafted name',
                'avgGoals' => [
                    $recordKey => [
                        'goal' => 'Drafted goal',
                        'avg_goal_legal_base' => $avgGoal->avg_goal_legal_base,
                        'remarks' => 'Drafted remarks',
                        'organisation_id' => $organisation->id->toString(),
                    ],
                ],
            ],
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->call('restoreDraft');

    $testable = $this->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->assertNotified(__('draft.restored'))
        ->assertSet('data.name', 'Drafted name')
        ->assertSet('data.avgGoals.' . $recordKey . '.goal', 'Drafted goal');

    $testable->call('save')
        ->assertHasNoFormErrors();

    expect($avgResponsibleProcessingRecord->refresh()->avgGoals()->count())
        ->toBe(1)
        ->and($avgGoal->refresh()->goal)
        ->toBe('Drafted goal');
});

it('restores nothing when the offered draft is gone', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create(['name' => 'Saved name']);
    $formDraft = FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
            'payload' => ['name' => 'Drafted name'],
        ]);

    $testable = $this->asFilamentUser($user)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id]);

    $formDraft->delete();

    $testable->call('restoreDraft');

    $this->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->assertNotNotified(__('draft.restored'))
        ->assertSet('data.name', 'Saved name');
});

it('keeps the draft when autosave is disabled and the record is saved', function (): void {
    ConfigTestHelper::set('autosave.enabled', false);

    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->withValidState()
        ->create();
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(EditAvgResponsibleProcessingRecord::class, ['record' => $avgResponsibleProcessingRecord->id])
        ->assertSet('draftKey', null)
        ->call('save')
        ->assertHasNoFormErrors();

    expect(FormDraft::all())
        ->toHaveCount(1);
});
