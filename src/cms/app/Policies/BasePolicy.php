<?php

declare(strict_types=1);

namespace App\Policies;

/**
 * @codeCoverageIgnore
 */
abstract class BasePolicy
{
    public function view(): bool
    {
        return false;
    }

    public function viewAny(): bool
    {
        return $this->view();
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

    public function deleteAny(): bool
    {
        return $this->delete();
    }

    public function forceDelete(): bool
    {
        return $this->delete();
    }

    public function forceDeleteAny(): bool
    {
        return $this->delete();
    }

    public function restore(): bool
    {
        return false;
    }

    public function restoreAny(): bool
    {
        return $this->restore();
    }
}
