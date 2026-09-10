<?php

declare(strict_types=1);

use App\Models\FormDraft;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

it('stores the payload encrypted', function (): void {
    $formDraft = FormDraft::factory()->create([
        'payload' => ['name' => 'very secret draft'],
    ]);

    $rawPayload = DB::table('form_drafts')
        ->where('id', $formDraft->id->toString())
        ->value('payload');

    expect($rawPayload)
        ->not->toContain('very secret draft')
        ->and($formDraft->refresh()->payload)
        ->toBe(['name' => 'very secret draft']);
});

it('allows one draft per user, resource and draft key', function (): void {
    $formDraft = FormDraft::factory()->create();

    FormDraft::factory()->create([
        'user_id' => $formDraft->user_id,
        'resource_class' => $formDraft->resource_class,
        'draft_key' => $formDraft->draft_key,
    ]);
})->throws(QueryException::class, 'duplicate key value violates unique constraint');

it('belongs to a user', function (): void {
    $formDraft = FormDraft::factory()->create();

    expect($formDraft->user->id->toString())
        ->toBe($formDraft->user_id->toString());
});
