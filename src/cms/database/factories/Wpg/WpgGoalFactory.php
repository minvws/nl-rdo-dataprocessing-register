<?php

declare(strict_types=1);

namespace Database\Factories\Wpg;

use App\Models\Organisation;
use App\Models\Wpg\WpgGoal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WpgGoal>
 */
class WpgGoalFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),

            'description' => $this->faker->text(),
            'article_8' => $this->faker->boolean(),
            'article_9' => $this->faker->boolean(),
            'article_10_1a' => $this->faker->boolean(),
            'article_10_1b' => $this->faker->boolean(),
            'article_10_1c' => $this->faker->boolean(),
            'article_12' => $this->faker->boolean(),
            'article_13_1' => $this->faker->boolean(),
            'article_13_2' => $this->faker->boolean(),
            'article_13_3' => $this->faker->boolean(),
            'explanation' => $this->faker->optional()->text(),
            'import_id' => $this->faker->optional()->importId(),
        ];
    }
}
