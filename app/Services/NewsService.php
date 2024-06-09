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
        $this->apiKey = ; //PLEASE CHANGE
        $this->apiHost = 'news-api14.p.rapidapi.com';
        $this->logger = $logger;
    }

    public function getNews($category = 'sports')
    {
        if (env('USE_MOCK_DATA', false)) {
            return $this->getMockNews($category);
        }

        try {
            $response = $this->client->request('GET', "https://news-api14.p.rapidapi.com/top-headlines", [
                'headers' => [
                    'x-rapidapi-host' => $this->apiHost,
                    'x-rapidapi-key' => $this->apiKey,
                ],
                'query' => [
                    'country' => 'us',
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
        return [
            'status' => 'ok',
            'totalResults' => 1,
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
