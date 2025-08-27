<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function sprintf;

class UserList extends Command
{
    protected $signature = 'user:list {--filter=}';
    protected $description = 'List current users';

    public function handle(): int
    {
        $userQuery = User::query();

        $filter = $this->option('filter');
        if ($filter !== null) {
            $filter = sprintf('%%%s%%', $filter);
            $userQuery->whereLike('email', $filter);
            $userQuery->orWhereLike('name', $filter);
        }

        $users = $userQuery->get(['name', 'email'])->toArray();

        $this->table(['Name', 'Email'], $users);

        return self::SUCCESS;
    }
}
