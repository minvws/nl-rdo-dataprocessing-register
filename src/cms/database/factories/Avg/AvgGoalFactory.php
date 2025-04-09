<?php

declare(strict_types=1);

namespace Database\Factories\Avg;

use App\Models\Avg\AvgGoal;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AvgGoal>
 */
class AvgGoalFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'organisation_id' => Organisation::factory(),

            'goal' => $this->faker->sentence(),
            'import_id' => $this->faker->optional()->importId(),

            'avg_goal_legal_base' => $this->faker->randomElement(['Toestemming betrokkene', 'Uitvoering overeenkomst']),
            'remarks' => $this->faker->optional()->text(),
        ];
    }
}
