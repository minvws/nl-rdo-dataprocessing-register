<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\PublicWebsiteTree;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PublicWebsiteTree>
 */
class PublicWebsiteTreeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'title' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'public_from' => $this->faker->dateTime(),
            'public_website_content' => $this->faker->paragraph(),
        ];
    }
}
