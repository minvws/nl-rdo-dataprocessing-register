<?php

declare(strict_types=1);

namespace App\Import;

interface Importer
{
    /**
     * @param class-string<Factory> $factoryClass
     */
    public function import(string $zipFilename, string $input, string $factoryClass, string $userId, string $organisationId): void;
}
