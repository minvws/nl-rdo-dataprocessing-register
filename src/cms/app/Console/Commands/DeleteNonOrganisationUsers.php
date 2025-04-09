<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteNonOrganisationUsers extends Command
{
    protected $signature = 'app:delete-non-organisation-users';
    protected $description = 'Delete users that are not attached to an organisation';

    public function handle(): int
    {
        $users = User::withoutGlobalScopes()
            ->doesntHave('organisations')
            ->limit(100)
            ->get();

        foreach ($users as $user) {
            $user->delete();
        }

        return self::SUCCESS;
    }
}
