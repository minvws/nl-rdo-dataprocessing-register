<?php

declare(strict_types=1);

namespace MinVWS\Logging\Laravel\Loggers;

use MinVWS\Logging\Laravel\Contracts\LoggableUser;

interface LogEventInterface
{
    // Get the user who triggered the log event
    public function getActor(): ?LoggableUser;

    // Get the user who is the target of the event (ie: "mr admin" (actor) disables account "john doe" (target)
    public function getTargetUser(): ?LoggableUser;

    // Get non-personal data to log
    public function getLogData(): array;

    // Get personal data to log
    public function getPiiLogData(): array;

    // Get merged array with non-personal and personal data
    public function getMergedPiiData(): array;

    // Returns the event key on which to log
    public function getEventKey(): string;
}
