<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PublicWebsite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PublicWebsite>
 */
class PublicWebsiteFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),

            'home_content' => $this->faker->optional()->sentence(),
        ];
    }
}
