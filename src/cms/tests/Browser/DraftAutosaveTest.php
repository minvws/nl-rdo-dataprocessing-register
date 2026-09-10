<?php

declare(strict_types=1);

use App\Enums\Authorization\Permission;
use App\Enums\RegisterLayout;
use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecordService;
use App\Models\FormDraft;
use App\Models\Responsible;
use Tests\Helpers\ConfigTestHelper;
use Tests\Helpers\Model\OrganisationTestHelper;
use Tests\Helpers\Model\UserTestHelper;

beforeEach(function (): void {
    ConfigTestHelper::set('autosave.poll_interval_seconds', 2);
});

it('automatically saves a draft while editing', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions(
        $organisation,
        Permission::cases(),
        ['register_layout' => RegisterLayout::ONE_PAGE],
    );
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $url = AvgResponsibleProcessingRecordResource::getUrl(
        'edit',
        ['record' => $avgResponsibleProcessingRecord],
        isAbsolute: false,
        tenant: $organisation,
    );

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertPresent('[id="form.name"]')
        ->clear('[id="form.name"]')
        ->typeSlowly('[id="form.name"]', 'Drafted in the browser')
        ->assertVisible('.fi-draft-autosave-saved')
        ->wait(3);

    $formDraft = FormDraft::all()->sole();

    expect($formDraft->payload['name'])
        ->toBe('Drafted in the browser');
});

it('saves a dropdown change that fires no input event', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions(
        $organisation,
        Permission::cases(),
        ['register_layout' => RegisterLayout::ONE_PAGE],
    );
    $avgResponsibleProcessingRecordService = AvgResponsibleProcessingRecordService::factory()
        ->recycle($organisation)
        ->create(['enabled' => true]);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $url = AvgResponsibleProcessingRecordResource::getUrl(
        'edit',
        ['record' => $avgResponsibleProcessingRecord],
        isAbsolute: false,
        tenant: $organisation,
    );

    $selector = '[id="form.avg_responsible_processing_record_service_id"]';

    $this->loginAs($user, $url)
        ->assertPresent($selector)
        ->select($selector, $avgResponsibleProcessingRecordService->id->toString())
        ->wait(5);

    expect(FormDraft::all()->sole()->payload['avg_responsible_processing_record_service_id'])
        ->toBe($avgResponsibleProcessingRecordService->id->toString());
});

it('keeps saving after the first change', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions(
        $organisation,
        Permission::cases(),
        ['register_layout' => RegisterLayout::ONE_PAGE],
    );
    $first = Responsible::factory()
        ->recycle($organisation)
        ->create(['name' => 'First']);
    $second = Responsible::factory()
        ->recycle($organisation)
        ->create(['name' => 'Second']);
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $url = AvgResponsibleProcessingRecordResource::getUrl(
        'edit',
        ['record' => $avgResponsibleProcessingRecord],
        isAbsolute: false,
        tenant: $organisation,
    );

    $wire = 'window.Livewire.all().find((c) => c.el.querySelector(\'[id="form.name"]\')).$wire';

    $page = $this->loginAs($user, $url)
        ->assertPresent('[id="form.responsible_id"]');

    $page->assertScript(sprintf("%s.set('data.responsible_id', ['%s'], false), 'set'", $wire, $first->id), 'set')
        ->wait(5);

    expect(FormDraft::all()->sole()->payload['responsible_id'])
        ->toBe([$first->id->toString()]);

    $page->assertScript(
        sprintf("%s.set('data.responsible_id', ['%s', '%s'], false), 'set'", $wire, $first->id, $second->id),
        'set',
    )->wait(5);

    expect(FormDraft::all()->sole()->payload['responsible_id'])
        ->toBe([$first->id->toString(), $second->id->toString()]);
});

it('does not save anything while the form is untouched', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions(
        $organisation,
        Permission::cases(),
        ['register_layout' => RegisterLayout::ONE_PAGE],
    );
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    $url = AvgResponsibleProcessingRecordResource::getUrl(
        'edit',
        ['record' => $avgResponsibleProcessingRecord],
        isAbsolute: false,
        tenant: $organisation,
    );

    $this->loginAs($user, $url)
        ->assertPresent('[id="form.name"]')
        ->wait(5);

    expect(FormDraft::all())
        ->toHaveCount(0);
});

it('offers a draft after a reload and restores it', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions(
        $organisation,
        Permission::cases(),
        ['register_layout' => RegisterLayout::ONE_PAGE],
    );
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
            'payload' => ['name' => 'Drafted in the browser'],
        ]);
    $url = AvgResponsibleProcessingRecordResource::getUrl(
        'edit',
        ['record' => $avgResponsibleProcessingRecord],
        isAbsolute: false,
        tenant: $organisation,
    );

    $this->loginAs($user, $url)
        ->assertPathIs($url)
        ->assertSee(__('draft.restore'))
        ->click(__('draft.restore'))
        ->assertSee(__('draft.restored'))
        ->assertScript("document.getElementById('form.name')?.value", 'Drafted in the browser');
});

it('replaces the offered draft after confirming', function (): void {
    $organisation = OrganisationTestHelper::create();
    $user = UserTestHelper::createForOrganisationWithPermissions(
        $organisation,
        Permission::cases(),
        ['register_layout' => RegisterLayout::ONE_PAGE],
    );
    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()
        ->recycle($organisation)
        ->create();
    FormDraft::factory()
        ->recycle($organisation)
        ->create([
            'user_id' => $user->id->toString(),
            'record_id' => $avgResponsibleProcessingRecord->id->toString(),
            'draft_key' => $avgResponsibleProcessingRecord->id->toString(),
            'payload' => ['name' => 'Offered draft'],
        ]);
    $url = AvgResponsibleProcessingRecordResource::getUrl(
        'edit',
        ['record' => $avgResponsibleProcessingRecord],
        isAbsolute: false,
        tenant: $organisation,
    );

    $this->loginAs($user, $url)
        ->assertSee(__('draft.restore'))
        ->type('[id="form.name"]', 'Typed over the offer')
        ->wait(4)
        ->assertSee(__('draft.overwrite_heading'))
        ->click(__('draft.overwrite_confirm'))
        ->wait(4)
        ->assertDontSee(__('draft.restore'));

    expect(FormDraft::all()->sole()->payload['name'])
        ->toBe('Typed over the offer');
});
