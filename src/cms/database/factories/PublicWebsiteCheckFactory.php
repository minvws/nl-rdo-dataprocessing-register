<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PublicWebsiteCheck;
use Illuminate\Database\Eloquent\Factories\Factory;
use Webmozart\Assert\Assert;

/**
 * @extends Factory<PublicWebsiteCheck>
 */
class PublicWebsiteCheckFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),

            'build_date' => $this->faker->dateTime(),
            'content' => $this->createContent(),
        ];
    }

    public function createForSnapshot(string $id): PublicWebsiteCheck
    {
        /** @var PublicWebsiteCheck $publicWebsiteCheck */
        $publicWebsiteCheck = $this->create([
            'content' => [
                'date' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
                'pages' => [
                    'id' => $id,
                    'permalink' => $this->faker->url(),
                    'type' => 'processing-record',
                ],
            ],
        ]);

        return $publicWebsiteCheck;
    }

    /**
     * @return array{'date': string, 'pages': array<array{'id': string, 'permalink': string, 'type': string}>}
     */
    private function createContent(): array
    {
        $pages = [];
        for ($i = 0; $i < $this->faker->randomDigit(); $i++) {
            $pages[] = $this->createPage();
        }

        return [
            'date' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'pages' => $pages,
        ];
    }

    /**
     * @return array{'id': string, 'permalink': string, 'type': string}
     */
    private function createPage(): array
    {
        $type = $this->faker->randomElement(['page', 'processing-record']);
        Assert::string($type);

        return [
            'id' => $this->faker->uuid(),
            'permalink' => $this->faker->url(),
            'type' => $type,
        ];
    }
}
