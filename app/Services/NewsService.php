<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;

class NewsService
{
    protected $client;
    protected $apiKey;
    protected $apiHost;
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->client = new Client();
        $this->apiKey = '5164abd0bbmshc95f63dc5d1553fp12000cjsne250ceceb573';
        $this->apiHost = 'news-api14.p.rapidapi.com';
        $this->logger = $logger;
    }

    public function getNews($category)
    {
        $useMockData = env('USE_MOCK_DATA', false);
        $this->logger->info('USE_MOCK_DATA value:', ['value' => $useMockData]);

        if ($useMockData) {
            $this->logger->info('Using mock data for news');
            return $this->getMockNews($category);
        }

        try {
            $response = $this->client->request('GET', "https://news-api14.p.rapidapi.com/top-headlines", [
                'headers' => [
                    'x-rapidapi-host' => $this->apiHost,
                    'x-rapidapi-key' => $this->apiKey,
                ],
                'query' => [
                    'country' => 'ph',
                    'language' => 'en',
                    'pageSize' => 10,
                    'category' => $category,
                    'sortBy' => 'title',
                ],
                'verify' => false
            ]);

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            // Log the entire response body for debugging
            $this->logger->error('API response error', ['body' => $responseBody]);

            $message = isset($responseBody['message']) ? $responseBody['message'] : 'An error occurred';

            throw new \Exception("Error fetching news data: $message", $statusCode);
        } catch (\Exception $e) {
            throw new \Exception('Error fetching news data: ' . $e->getMessage(), 500);
        }
    }

    private function getMockNews($category)
    {
        $this->logger->info('Returning mock news data', ['category' => $category]);

        return [
            'status' => 'ok',
            'totalResults' => 5,
            'articles' => [
                [
                    'source' => [
                        'id' => null,
                        'name' => 'Mock News Source',
                    ],
                    'author' => 'Mock Author',
                    'title' => 'This is a mock news title for ' . $category,
                    'description' => 'This is a mock news description.',
                    'url' => 'https://example.com/mock-news',
                    'urlToImage' => 'https://example.com/mock-image.jpg',
                    'publishedAt' => '2024-06-08T00:00:00Z',
                    'content' => 'This is mock news content for testing purposes.',
                ],
            ],
        ];
    }
}
