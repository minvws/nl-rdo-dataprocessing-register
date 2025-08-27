<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Collections\OrganisationCollection;
use App\Enums\Authorization\Role;
use App\Enums\EntityNumberType;
use App\Models\EntityNumberCounter;
use App\Models\Organisation;
use App\Models\PublicWebsiteTree;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

use function __;
use function sprintf;

class TestDataSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $organisations = $this->createOrganisations();
        $this->createUsers($organisations);
        $this->createPublicWebsiteTree($organisations);
    }

    private function createOrganisations(): OrganisationCollection
    {
        $organisations = Organisation::factory()
            ->withAllRelatedEntities()
            ->count(4)
            ->state(new Sequence(
                [
                    'name' => 'RIVM',
                    'slug' => 'rivm',
                    'register_entity_number_counter_id' => EntityNumberCounter::factory([
                        'prefix' => 'RIVM',
                        'type' => EntityNumberType::REGISTER,
                    ]),
                    'databreach_entity_number_counter_id' => EntityNumberCounter::factory([
                        'prefix' => 'RIVMD',
                        'type' => EntityNumberType::DATABREACH,
                    ]),
                ],
                [
                    'name' => 'Gezondheidsraad',
                    'slug' => 'gezr',
                    'register_entity_number_counter_id' => EntityNumberCounter::factory([
                        'prefix' => 'GEZR',
                        'type' => EntityNumberType::REGISTER,
                    ]),
                    'databreach_entity_number_counter_id' => EntityNumberCounter::factory([
                        'prefix' => 'GEZRD',
                        'type' => EntityNumberType::DATABREACH,
                    ]),
                ],
                [
                    'name' => 'Sociaal en Cultureel Planbureau',
                    'slug' => 'secp',
                    'register_entity_number_counter_id' => EntityNumberCounter::factory([
                        'prefix' => 'SECP',
                        'type' => EntityNumberType::REGISTER,
                    ]),
                    'databreach_entity_number_counter_id' => EntityNumberCounter::factory([
                        'prefix' => 'SECPD',
                        'type' => EntityNumberType::DATABREACH,
                    ]),
                ],
                [
                    'name' => 'Rijksdienst voor Ondernemend Nederland',
                    'slug' => 'rvon',
                    'register_entity_number_counter_id' => EntityNumberCounter::factory([
                        'prefix' => 'RVON',
                        'type' => EntityNumberType::REGISTER,
                    ]),
                    'databreach_entity_number_counter_id' => EntityNumberCounter::factory([
                        'prefix' => 'RVOND',
                        'type' => EntityNumberType::DATABREACH,
                    ]),
                ],
            ))
            ->create([
                'allowed_ips' => '*.*.*.*',
            ]);
        Assert::isInstanceOf($organisations, OrganisationCollection::class);

        return $organisations;
    }

    private function createUsers(OrganisationCollection $organisations): void
    {
        $organisation = $organisations->firstOrFail();

        User::factory()
            ->hasAttached($organisations)
            ->hasGlobalRole(Role::FUNCTIONAL_MANAGER)
            ->hasOrganisationRole(Role::CHIEF_PRIVACY_OFFICER, $organisation)
            ->hasOrganisationRole(Role::COUNSELOR, $organisation)
            ->hasOrganisationRole(Role::DATA_PROTECTION_OFFICIAL, $organisation)
            ->hasOrganisationRole(Role::INPUT_PROCESSOR, $organisation)
            ->hasOrganisationRole(Role::INPUT_PROCESSOR_DATABREACH, $organisation)
            ->hasOrganisationRole(Role::MANDATE_HOLDER, $organisation)
            ->hasOrganisationRole(Role::PRIVACY_OFFICER, $organisation)
            ->withValidOtpRegistration()
            ->create($this->createUserProperties('Admin'));

        // test users
        foreach (Role::globalRoles() as $globalRole) {
            $this->createUserWithGlobalRole($globalRole);
        }
        foreach (Role::organisationRoles() as $organisationRole) {
            $this->createUserWithOrganisationRole($organisation, $organisationRole);
        }
    }

    private function createUserWithGlobalRole(Role $role): void
    {
        User::factory()
            ->hasGlobalRole($role)
            ->withValidOtpRegistration()
            ->create($this->createUserProperties(__(sprintf('role.%s', $role->value))));
    }

    private function createUserWithOrganisationRole(Organisation $organisation, Role $role): void
    {
        User::factory()
            ->hasAttached($organisation)
            ->hasOrganisationRole($role, $organisation)
            ->withValidOtpRegistration()
            ->create($this->createUserProperties(__(sprintf('role.%s', $role->value))));
    }

    /**
     * @return array<string, string>
     */
    private function createUserProperties(string $name): array
    {
        return [
            'name' => $name,
            'email' => sprintf('%s@minvws.nl', Str::of($name)->slug()),
        ];
    }

    private function createPublicWebsiteTree(OrganisationCollection $organisations): void
    {
        foreach ($organisations as $organisation) {
            PublicWebsiteTree::factory()
                ->recycle($organisation)
                ->create();
        }
    }
}
