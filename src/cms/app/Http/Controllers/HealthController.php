<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DatabaseHealthService;
use App\Services\Virusscanner\Virusscanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

use function response;

use const JSON_PRETTY_PRINT;

readonly class HealthController
{
    public function __construct(
        private DatabaseHealthService $databaseHealthService,
        private Virusscanner $virusscanner,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $databaseHealth = $this->databaseHealthService->isHealthy();
        $virusscannerHealth = $this->virusscanner->isHealthy();

        $isHealthy = $databaseHealth && $virusscannerHealth;

        return response()->json(
            [
                'healthy' => $isHealthy,
                'externals' => [
                    'database' => $databaseHealth,
                    'virusscanner' => $virusscannerHealth,
                ],
            ],
            $isHealthy ? Response::HTTP_OK : Response::HTTP_SERVICE_UNAVAILABLE,
            [],
            JSON_PRETTY_PRINT,
        );
    }
}
