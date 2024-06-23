<?php

namespace App\Services;

use GuzzleHttp\Client; // For making HTTP response
use GuzzleHttp\Exception\ClientException; // Handling HTTP client exceptions
use Psr\Log\LoggerInterface; // For logging messages

class NewsService
{
    protected $client; // To hold the HTTP client instance
    protected $apiKey; // To hold the API key
    protected $apiHost; // To hold the API host URL
    protected $logger; // To hold the logger instance

    // Constructor method that takes LoggerInterface as a parameter
    public function __construct(LoggerInterface $logger)
    {
        $this->client = new Client(); // Initializes the HTTP client using Guzzle
        $this->apiKey = '5164abd0bbmshc95f63dc5d1553fp12000cjsne250ceceb573'; // Sets the API key
        $this->apiHost = 'news-api14.p.rapidapi.com'; // Sets the API host URL
        $this->logger = $logger; // Sets the logger instance
    }

    // Method to fetch news 
    public function getNews($category)
    {
        $useMockData = env('USE_MOCK_DATA', false); // Retrieves the USE_MOCK_DATA, defaulting to false if not set
        $this->logger->info('USE_MOCK_DATA value:', ['value' => $useMockData]); // Logs the value of USE_MOCK_DATA

        // Checks if mock data should be used
        if ($useMockData) {
            $this->logger->info('Using mock data for news'); // Logs that mock data is being used
            return $this->getMockNews($category); // Returns mock news data if USE_MOCK_DATA is true
        } 

        // Starts a try block to attempt the API request
        try {
            $response = $this->client->request('GET', "https://news-api14.p.rapidapi.com/top-headlines", [ // Makes a GET request to the news API endpoint 
                // Sets the headers for the request
                'headers' => [
                    'x-rapidapi-host' => $this->apiHost,
                    'x-rapidapi-key' => $this->apiKey,
                ],
                // Sets the query parameters for the request
                'query' => [
                    'country' => 'ph',
                    'language' => 'en',
                    'pageSize' => 10,
                    'category' => $category,
                    'sortBy' => 'title',
                ],
                'verify' => false // Disables SSL certificate verification 
            ]);
            
            // Returns the decoded JSON response body
            return json_decode($response->getBody(), true); 
        } catch (ClientException $e) { // Catches ClientException errors
            $response = $e->getResponse(); // Gets the response from the exception
            $statusCode = $response->getStatusCode(); // Gets the status code from the response
            $responseBody = json_decode($response->getBody()->getContents(), true); // Decodes the response body

            // Log the entire response body for debugging
            $this->logger->error('API response error', ['body' => $responseBody]);

            // Gets the error message from the response or sets a default message
            $message = isset($responseBody['message']) ? $responseBody['message'] : 'An error occurred'; 

            // Throws a new exception with the error message and status code
            throw new \Exception("Error fetching news data: $message", $statusCode); 
        } catch (\Exception $e) { // Catches any other exceptions
            throw new \Exception('Error fetching news data: ' . $e->getMessage(), 500); // Throws a new exception with a generic error message and a status code of 500 
        }
    }
    
    // Method to get mock news data
    private function getMockNews($category)
    {
        // Mock news data is being returned 
        $this->logger->info('Returning mock news data', ['category' => $category]);

        // Returns an array with mock news data
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
