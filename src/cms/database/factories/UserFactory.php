<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'remember_token' => Str::random(10),
            'otp_secret' => $this->faker->regexify('[A-Z]{16}'),
            'otp_confirmed_at' => $this->faker->optional()->dateTime(),
            'otp_timestamp' => $this->faker->numberBetween(),
        ];
    }

    public function withOrganisation(): self
    {
        return $this->afterCreating(static function (User $user): void {
            Organisation::factory()
                ->hasAttached($user)
                ->create();
        });
    }

    public function withValidOtpRegistration(): self
    {
        return $this->afterMaking(function (User $user): void {
            $user->otp_secret = $this->faker->regexify('[A-Z]{16}');
            $user->otp_confirmed_at = CarbonImmutable::now()->subHour();
        });
    }
}
