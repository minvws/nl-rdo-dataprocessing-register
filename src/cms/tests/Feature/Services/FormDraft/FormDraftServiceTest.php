<?php

declare(strict_types=1);

use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Models\FormDraft;
use App\Models\Organisation;
use App\Models\User;
use App\Services\FormDraft\FormDraftService;
use Illuminate\Support\Facades\App;

it('keeps the existing draft when the payload is unchanged', function (): void {
    $organisation = Organisation::factory()->create();
    $user = User::factory()->create();
    $payload = ['name' => fake()->word()];

    $formDraft = FormDraft::factory()->create([
        'organisation_id' => $organisation->id->toString(),
        'user_id' => $user->id->toString(),
        'resource_class' => AvgResponsibleProcessingRecordResource::class,
        'payload' => $payload,
    ]);

    /** @var FormDraftService $formDraftService */
    $formDraftService = App::make(FormDraftService::class);

    $this->travel(5)->minutes();

    $result = $formDraftService->save(
        $user,
        $organisation,
        AvgResponsibleProcessingRecordResource::class,
        null,
        $formDraft->draft_key->toString(),
        $payload,
    );

    expect($result->id->toString())
        ->toBe($formDraft->id->toString())
        ->and($result->updated_at->getTimestamp())
        ->toBe($formDraft->updated_at->getTimestamp());
});
