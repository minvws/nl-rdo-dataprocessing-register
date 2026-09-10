<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Filament\Resources\AvgResponsibleProcessingRecordResource;
use App\Models\FormDraft;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FormDraft>
 */
class FormDraftFactory extends Factory
{
    protected $model = FormDraft::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'user_id' => User::factory(),
            'resource_class' => AvgResponsibleProcessingRecordResource::class,
            'record_id' => null,
            'draft_key' => $this->faker->uuid(),
            'payload' => ['name' => $this->faker->word()],
        ];
    }
}
