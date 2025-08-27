<?php

declare(strict_types=1);

namespace App\Services\JsonRequest;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

use function is_object;
use function json_decode;

readonly class JsonRequestService
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws JsonException
     * @throws RequestException
     */
    public function makeRequest(string $url, ?string $proxy): object
    {
        $options = [
            'timeout' => 5,
        ];

        if ($proxy !== null) {
            $options['proxy'] = $proxy;
        }

        try {
            $response = Http::withOptions($options)->get($url);
        } catch (ConnectionException $connectionException) {
            $this->logger->notice('public website check connection exception', ['exception' => $connectionException]);

            throw new RequestException('connection exception');
        }

        if (!$response->ok()) {
            $this->logger->notice('public website check response non-ok', ['responseStatus' => $response->status()]);

            throw new RequestException('non-ok response');
        }

        $responseContent = $response->body();
        if (!Str::isJson($responseContent)) {
            throw new JsonException('invalid json found in response');
        }

        $decodedResponseContent = json_decode($responseContent);
        if (!is_object($decodedResponseContent)) {
            throw new JsonException('invalid json-format found in response');
        }

        return $decodedResponseContent;
    }
}
