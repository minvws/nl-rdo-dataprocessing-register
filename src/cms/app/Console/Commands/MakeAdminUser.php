<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Authorization\Role;
use App\Enums\EntityNumberType;
use App\Models\EntityNumberCounter;
use App\Models\Organisation;
use App\Models\ResponsibleLegalEntity;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Throwable;

use function filter_var;
use function Laravel\Prompts\text;

use const FILTER_VALIDATE_EMAIL;

class MakeAdminUser extends Command
{
    protected $signature = 'make:admin-user';
    protected $description = 'Create a new admin user';

    public function handle(): int
    {
        $inputData = $this->getInputData();

        try {
            $organisation = $this->createOrGetOrganisation($inputData['organisation']);
            $this->createUser($inputData['name'], $inputData['email'], $organisation);
        } catch (Throwable $throwable) {
            $this->output->error($throwable->getMessage());

            return self::FAILURE;
        }

        $this->output->success('User created');

        return self::SUCCESS;
    }

    /**
     * @return array{'name': string, 'email': string, 'organisation': string}
     */
    private function getInputData(): array
    {
        return [
            'name' => text(label: 'Name', default: 'admin', required: true),
            'email' => text(
                label: 'Email address',
                default: 'admin@minvws.nl',
                required: true,
                validate: function (string $email): ?string {
                    return match (true) {
                        $this->isInvalidEmail($email) => 'The email address must be valid.',
                        $this->userWithEmailExists($email) => 'A user with this email address already exists',
                        default => null,
                    };
                },
            ),
            'organisation' => text(label: 'Organisation', default: 'MinVWS', required: true),
        ];
    }

    private function isInvalidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== $email;
    }

    private function userWithEmailExists(string $email): bool
    {
        return User::where('email', $email)
            ->exists();
    }

    private function createOrGetOrganisation(string $organisationName): Organisation
    {
        $responsibleLegalEntity = ResponsibleLegalEntity::firstOrFail();

        $organisation = Organisation::firstOrNew(['slug' => Str::slug($organisationName)]);

        if ($organisation->exists) {
            return $organisation;
        }

        $databreachEntityNumberPrefix = $this->createEntityNumberCounter(EntityNumberType::DATABREACH, $organisationName);
        $registerEntityNumberPrefix = $this->createEntityNumberCounter(EntityNumberType::REGISTER, $organisationName);

        return Organisation::firstOrCreate([
            'slug' => Str::slug($organisationName),
        ], [
            'name' => $organisationName,
            'allowed_ips' => '*.*.*.*',
            'responsible_legal_entity_id' => $responsibleLegalEntity->id,
            'databreach_entity_number_counter_id' => $databreachEntityNumberPrefix->id,
            'register_entity_number_counter_id' => $registerEntityNumberPrefix->id,
        ]);
    }

    private function createUser(string $name, string $email, Organisation $organisation): void
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
        ]);

        $user->assignGlobalRole(Role::CHIEF_PRIVACY_OFFICER);
        $user->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

        $user->assignOrganisationRole(Role::COUNSELOR, $organisation);
        $user->assignOrganisationRole(Role::DATA_PROTECTION_OFFICIAL, $organisation);
        $user->assignOrganisationRole(Role::INPUT_PROCESSOR, $organisation);
        $user->assignOrganisationRole(Role::MANDATE_HOLDER, $organisation);
        $user->assignOrganisationRole(Role::PRIVACY_OFFICER, $organisation);

        $user->organisations()->attach($organisation);
    }

    private function createEntityNumberCounter(EntityNumberType $entityNumberType, string $organisationName): EntityNumberCounter
    {
        $exists = EntityNumberCounter::where('prefix', $organisationName)
            ->where('type', $entityNumberType)
            ->exists();

        if ($exists) {
            throw new Exception('Orgnaisation-prefix already exists');
        }

        return EntityNumberCounter::create([
            'type' => $entityNumberType,
            'prefix' => $organisationName,
        ]);
    }
}
