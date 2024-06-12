<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;

class QuoteService
{
    protected $client;
    protected $apiKey;
    protected $apiHost;
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->client = new Client();
        $this->apiKey = '5164abd0bbmshc95f63dc5d1553fp12000cjsne250ceceb573';
        $this->apiHost = 'quotes-by-api-ninjas.p.rapidapi.com';
        $this->logger = $logger;
    }

    public function getRandomQuote($word = null)
    {
        $useMockData = env('USE_MOCK_DATA', false);

        if ($useMockData) {
            return $this->getMockQuote($word);
        }

        try {
            $this->logger->info('Fetching quote from API', ['word' => $word]);
            $query = $word ? ['category' => $word] : [];
            
            $response = $this->client->request('GET', "https://{$this->apiHost}/v1/quotes", [
                'headers' => [
                    'x-rapidapi-host' => $this->apiHost,
                    'x-rapidapi-key' => $this->apiKey,
                ],
                'query' => $query,
                'verify' => false
            ]);

            $this->logger->info('API response status: ' . $response->getStatusCode());
            $this->logger->info('API response body: ' . $response->getBody());

            $data = json_decode($response->getBody(), true);
            if (!empty($data) && isset($data[0]['quote'])) {
                return ['quote' => $data[0]['quote']];
            } else {
                $this->logger->error('Quote not found in the API response');
                throw new \Exception('Quote not found');
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $message = json_decode($response->getBody()->getContents(), true)['message'] ?? 'Unknown error';

            $this->logger->error("ClientException: Error fetching quote: $message", ['status_code' => $statusCode]);
            throw new \Exception("Error fetching quote: $message", $statusCode);
        } catch (\Exception $e) {
            $this->logger->error('Exception: Error fetching quote: ' . $e->getMessage());
            throw new \Exception('Error fetching quote: ' . $e->getMessage(), 500);
        }
    }

    private function getMockQuote($word = null)
    {
        return [
            'quote' => $word ? "This is a mock quote for the word: $word" : 'This is a mock quote for testing purposes.'
        ];
    }
}
