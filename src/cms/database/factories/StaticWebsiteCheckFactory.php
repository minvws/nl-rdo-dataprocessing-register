<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Components\Uuid\UuidInterface;
use App\Models\StaticWebsiteCheck;
use Illuminate\Database\Eloquent\Factories\Factory;
use Webmozart\Assert\Assert;

/**
 * @extends Factory<StaticWebsiteCheck>
 */
class StaticWebsiteCheckFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'build_date' => $this->faker->dateTime(),
            'content' => $this->createContent(),
        ];
    }

    public function createForSnapshot(UuidInterface $id): StaticWebsiteCheck
    {
        /** @var StaticWebsiteCheck $staticWebsiteCheck */
        $staticWebsiteCheck = $this->create([
            'content' => [
                'date' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
                'pages' => [
                    'id' => $id->toString(),
                    'permalink' => $this->faker->url(),
                    'type' => 'processing-record',
                ],
            ],
        ]);

        return $staticWebsiteCheck;
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
