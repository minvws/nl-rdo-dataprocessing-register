<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Authorization\Role;
use App\Models\Organisation;
use App\Models\PublicWebsiteTree;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use function sprintf;

class TestDataSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->createOrganisations();
        $this->createUsers();
        $this->createPublicWebsiteTree();
    }

    private function createOrganisations(): void
    {
        Organisation::factory()
            ->withAllRelatedEntities()
            ->count(4)
            ->state(new Sequence(
                ['name' => 'RIVM', 'slug' => 'rivm'],
                ['name' => 'Gezondheidsraad', 'slug' => 'gezr'],
                ['name' => 'Sociaal en Cultureel Planbureau', 'slug' => 'secp'],
                ['name' => 'Rijksdienst voor Ondernemend Nederland', 'slug' => 'rvon'],
            ))
            ->create([
                'allowed_ips' => '*.*.*.*',
            ]);
    }

    private function createUsers(): void
    {
        $rivmOrganisation = Organisation::query()->where(['slug' => 'rivm'])->firstOrFail();

        // admin
        $adminUser = $this->createUser(Organisation::all(), 'Admin');

        $adminUser->assignGlobalRole(Role::CHIEF_PRIVACY_OFFICER);
        $adminUser->assignGlobalRole(Role::FUNCTIONAL_MANAGER);

        $adminUser->assignOrganisationRole(Role::COUNSELOR, $rivmOrganisation);
        $adminUser->assignOrganisationRole(Role::DATA_PROTECTION_OFFICIAL, $rivmOrganisation);
        $adminUser->assignOrganisationRole(Role::INPUT_PROCESSOR, $rivmOrganisation);
        $adminUser->assignOrganisationRole(Role::MANDATE_HOLDER, $rivmOrganisation);
        $adminUser->assignOrganisationRole(Role::PRIVACY_OFFICER, $rivmOrganisation);

        // test users
        $this->createUserWithGlobalRole($rivmOrganisation, 'Functioneel Manager', Role::FUNCTIONAL_MANAGER);

        $this->createUserWithOrganisationRole($rivmOrganisation, 'Chief Privacy Officer', Role::CHIEF_PRIVACY_OFFICER);
        $this->createUserWithOrganisationRole($rivmOrganisation, 'Raadpleger', Role::COUNSELOR);
        $this->createUserWithOrganisationRole($rivmOrganisation, 'Functionaris Gegevensbescherming', Role::DATA_PROTECTION_OFFICIAL);
        $this->createUserWithOrganisationRole($rivmOrganisation, 'Invoerder', Role::INPUT_PROCESSOR);
        $this->createUserWithOrganisationRole($rivmOrganisation, 'Mandaathouder', Role::MANDATE_HOLDER);
        $this->createUserWithOrganisationRole($rivmOrganisation, 'Privacy Officer', Role::PRIVACY_OFFICER);
    }

    /**
     * @param Organisation|Collection<int, Organisation> $organisations
     */
    private function createUserWithGlobalRole(Organisation|Collection $organisations, string $name, Role $role): void
    {
        $user = $this->createUser($organisations, $name);
        $user->assignGlobalRole($role);
    }

    private function createUserWithOrganisationRole(Organisation $organisation, string $name, Role $role): void
    {
        $user = $this->createUser($organisation, $name);
        $user->assignOrganisationRole($role, $organisation);
    }

    /**
     * @param Organisation|Collection<int, Organisation> $organisations
     */
    private function createUser(Organisation|Collection $organisations, string $name): User
    {
        return User::factory()
            ->hasAttached($organisations)
            ->withValidOtpRegistration()
            ->create([
                'name' => $name,
                'email' => sprintf('%s@minvws.nl', Str::of($name)->slug()),
            ]);
    }

    private function createPublicWebsiteTree(): void
    {
        PublicWebsiteTree::factory()
            ->count(5)
            ->create();
    }
}
