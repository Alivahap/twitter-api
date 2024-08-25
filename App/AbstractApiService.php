<?php
namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

abstract class AbstractApiService implements ApiServiceInterface
{
    protected Client $client;
    protected string $bearerToken;

    public function __construct(string $bearerToken, string $baseUri)
    {
        $this->bearerToken = $bearerToken;
        $this->client = new Client(['base_uri' => $baseUri]);
    }

    protected function sendRequest(string $method, string $url, array $headers = []): object
    {
        $headers['Authorization'] = 'Bearer ' . $this->bearerToken;

        try {
            $response = $this->client->request($method, $url, [
                'verify' => !$this->isWindows(),
                'headers' => $headers,
            ]);

            $body = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

            if ($response->getStatusCode() >= 400) {
                $this->handleErrorResponse($response);
            }

            return $body;
        } catch (RequestException $e) {
            $this->handleException($e);
        }
    }

    private function handleErrorResponse($response): void
    {
        if ($response->getStatusCode() === 429) {
            throw new \RuntimeException(
                json_encode(['message' => 'Sınırlı istek sayısına ulaştınız. Lütfen 15 dakika sonra tekrar deneyiniz.'], JSON_THROW_ON_ERROR),
                $response->getStatusCode()
            );
        } else {
            throw new \RuntimeException(
                json_encode(['message' => 'API error', 'details' => $response->getBody()->getContents()], JSON_THROW_ON_ERROR),
                $response->getStatusCode()
            );
        }
    }

    private function handleException(RequestException $e): void
    {
        if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 429) {
            throw new \RuntimeException(
                json_encode(['message' => 'Sınırlı istek sayısına ulaştınız. Lütfen 15 dakika sonra tekrar deneyiniz.'], JSON_THROW_ON_ERROR),
                429
            );
        } else {
            throw new \RuntimeException(
                json_encode(['message' => 'Request error', 'details' => $e->getMessage()], JSON_THROW_ON_ERROR),
                $e->getCode()
            );
        }
    }

    private function isWindows(): bool
    {
        return DIRECTORY_SEPARATOR === '\\';
    }
}
?>