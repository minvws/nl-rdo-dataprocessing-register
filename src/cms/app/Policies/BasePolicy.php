<?php

declare(strict_types=1);

namespace App\Policies;

/**
 * @codeCoverageIgnore
 */
abstract class BasePolicy
{
    public function viewAny(): bool
    {
        return $this->view();
    }

    public function view(): bool
    {
        return false;
    }

    public function create(): bool
    {
        return false;
    }

    public function update(): bool
    {
        return false;
    }

    public function delete(): bool
    {
        return false;
    }

    public function restore(): bool
    {
        return false;
    }

    public function forceDelete(): bool
    {
        return false;
    }
}
