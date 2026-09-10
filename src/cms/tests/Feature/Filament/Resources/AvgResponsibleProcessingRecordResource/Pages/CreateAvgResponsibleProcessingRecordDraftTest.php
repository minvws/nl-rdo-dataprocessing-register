<?php

declare(strict_types=1);

use App\Enums\CoreEntityDataCollectionSource;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource\Pages\CreateAvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use App\Models\FormDraft;
use App\Models\Responsible;
use Tests\Helpers\ConfigTestHelper;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

it('saves a draft without a record', function (): void {
    $organisation = OrganisationTestHelper::create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm(['name' => 'Drafted name'])
        ->call('saveDraft');

    $formDraft = FormDraft::all()->sole();

    expect($formDraft->record_id)
        ->toBeNull()
        ->and($formDraft->draft_key)
        ->not->toBeNull()
        ->and($formDraft->payload['name'])
        ->toBe('Drafted name');
});

it('saves nothing when autosave is disabled', function (): void {
    ConfigTestHelper::set('autosave.enabled', false);

    $organisation = OrganisationTestHelper::create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm(['name' => 'Drafted name'])
        ->call('saveDraft');

    expect(FormDraft::all())
        ->toBeEmpty();
});

it('deletes the draft when the record is created', function (): void {
    $organisation = OrganisationTestHelper::create();
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create([
            'enabled' => true,
        ]);
    $responsible = Responsible::factory()
        ->recycle($organisation)
        ->create();

    $this->asFilamentOrganisationUser($organisation)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm([
            'data_collection_source' => CoreEntityDataCollectionSource::PRIMARY->value,
            'name' => 'Drafted name',
            'avg_responsible_processing_record_service_id' => $avgResponsibleProcessingRecordService->id->toString(),
            'responsible_id' => [$responsible->id->toString()],
        ])
        ->call('saveDraft')
        ->call('create')
        ->assertHasNoFormErrors();

    expect(FormDraft::all())
        ->toHaveCount(0);
});

it('collapses older create drafts into a single slot on save', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    FormDraft::factory()
        ->recycle($organisation)
        ->count(3)
        ->create([
            'user_id' => $user->id->toString(),
        ]);

    $testable = $this->asFilamentUser($user)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm(['name' => 'Drafted name'])
        ->callAction('confirmDraftOverwrite')
        ->call('saveDraft');

    $formDraft = FormDraft::all()->sole();

    expect($formDraft->payload['name'])
        ->toBe('Drafted name');

    $testable->assertSet('draftKey', $formDraft->draft_key->toString())
        ->assertSet('restorableDraftKey', null)
        ->assertSet('restorableDraftSavedAt', null);
});

it('does not replace an offered draft before the overwrite is confirmed', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $offeredFormDraft = FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'payload' => ['name' => 'Offered draft'],
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->assertSet('restorableDraftKey', $offeredFormDraft->draft_key->toString())
        ->fillForm(['name' => 'Something else'])
        ->call('saveDraft')
        ->assertActionExists('confirmDraftOverwrite');

    expect(FormDraft::all())
        ->toHaveCount(1)
        ->and($offeredFormDraft->refresh()->payload['name'])
        ->toBe('Offered draft');
});

it('replaces the offered draft once the overwrite is confirmed', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'payload' => ['name' => 'Offered draft'],
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm(['name' => 'Something else'])
        ->callAction('confirmDraftOverwrite')
        ->assertSet('restorableDraftKey', null)
        ->assertDispatched('draft-overwrite-confirmed')
        ->call('saveDraft');

    expect(FormDraft::all()->sole()->payload['name'])
        ->toBe('Something else');
});

it('allows saving again after the offer is ignored', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'payload' => ['name' => 'Offered draft'],
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm(['name' => 'Something else'])
        ->call('ignoreDraft')
        ->assertSet('restorableDraftKey', null)
        ->call('saveDraft');

    expect(FormDraft::all()->sole()->payload['name'])
        ->toBe('Something else');
});

it('does not touch create drafts of another user or another organisation', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $otherUserFormDraft = FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => UserTestHelper::createForOrganisation($organisation)->id->toString(),
            'payload' => ['name' => 'Draft of another user'],
        ]);
    $otherOrganisationFormDraft = FormDraft::factory()
        ->create([
            'user_id' => $user->id->toString(),
            'payload' => ['name' => 'Draft in another organisation'],
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->fillForm(['name' => 'Drafted name'])
        ->call('saveDraft');

    expect(FormDraft::all())
        ->toHaveCount(3)
        ->and($otherUserFormDraft->refresh()->payload['name'])
        ->toBe('Draft of another user')
        ->and($otherOrganisationFormDraft->refresh()->payload['name'])
        ->toBe('Draft in another organisation');
});

it('offers the latest create draft and adopts its key on restore', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'payload' => ['name' => 'Older draft'],
            'updated_at' => now()->subHour(),
        ]);
    $latestFormDraft = FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'payload' => ['name' => 'Latest draft'],
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->assertSet('restorableDraftKey', $latestFormDraft->draft_key->toString())
        ->call('restoreDraft');

    $this->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->assertSet('data.name', 'Latest draft')
        ->assertSet('draftKey', $latestFormDraft->draft_key->toString());
});

it('restores nothing when no draft is offered', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => UserTestHelper::createForOrganisation($organisation)->id->toString(),
            'payload' => ['name' => 'Draft of another user'],
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->assertSet('restorableDraftKey', null)
        ->call('restoreDraft');

    $this->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->assertNotNotified(__('draft.restored'))
        ->assertSet('data.name', null);
});

it('offers the draft again when the restore was not picked up in time', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisation($organisation);
    $formDraft = FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'payload' => ['name' => 'Offered draft'],
        ]);

    $this->asFilamentUser($user)
        ->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->call('restoreDraft');

    $this->travel(2)->minutes();

    $this->createLivewireTestable(CreateAvgResponsibleProcessingRecord::class)
        ->assertNotNotified(__('draft.restored'))
        ->assertSet('data.name', null)
        ->assertSet('restorableDraftKey', $formDraft->draft_key->toString());
});
