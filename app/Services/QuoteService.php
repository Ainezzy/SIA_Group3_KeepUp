<?php

namespace App\Services;

use GuzzleHttp\Client; // For making HTTP response
use GuzzleHttp\Exception\ClientException; // Handling HTTP client exceptions
use Psr\Log\LoggerInterface; // For logging messages

class QuoteService
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
        $this->apiHost = 'quotes-by-api-ninjas.p.rapidapi.com'; // Sets the API host URL
        $this->logger = $logger; // Sets the logger instance
    }

    // Method to fetch a random quote
    public function getRandomQuote($word = null)
    {
        $useMockData = env('USE_MOCK_DATA', false); // // Retrieves the USE_MOCK_DATA, defaulting to false if not set

         // Checks if mock data should be used
        if ($useMockData) {
            return $this->getMockQuote($word); // // Returns mock quote data if USE_MOCK_DATA is true
        }

        // Starts a try block to attempt the API request
        try {
            $this->logger->info('Fetching quote from API', ['word' => $word]); // Logs the attempt to fetch a quote
            $query = $word ? ['category' => $word] : []; // Prepares query parameters
            
            $response = $this->client->request('GET', "https://{$this->apiHost}/v1/quotes", [ // Makes a GET request to the quote API
                 // Sets the headers for the request
                'headers' => [
                    'x-rapidapi-host' => $this->apiHost,
                    'x-rapidapi-key' => $this->apiKey,
                ],
                'query' => $query, // Sets the query parameters for the request 
                'verify' => false // Disables SSL certificate verification 
            ]);

            $this->logger->info('API response status: ' . $response->getStatusCode()); // Logs the response status code 
            $this->logger->info('API response body: ' . $response->getBody()); //  Logs the response body
            
            // Decodes the JSON response body
            $data = json_decode($response->getBody(), true); 

            // Checks if a quote is found in the response
            if (!empty($data) && isset($data[0]['quote'])) { 
                return ['quote' => $data[0]['quote']];
            } else {
                $this->logger->error('Quote not found in the API response');
                throw new \Exception('Quote not found');
            }
        } catch (ClientException $e) { // Handles Guzzle client exceptions, logs the error, and throws a new exception with the error message and status code
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $message = json_decode($response->getBody()->getContents(), true)['message'] ?? 'Unknown error';

            $this->logger->error("ClientException: Error fetching quote: $message", ['status_code' => $statusCode]);
            throw new \Exception("Error fetching quote: $message", $statusCode);
                                      
        } catch (\Exception $e) { // Handles other exceptions, logs the error, and throws a new exception with a generic error message
            $this->logger->error('Exception: Error fetching quote: ' . $e->getMessage());
            throw new \Exception('Error fetching quote: ' . $e->getMessage(), 500);
        }
    }
    // Private method to return mock quote data
    private function getMockQuote($word = null)
    {
        // Returns an array with a mock quote
        return [
            'quote' => $word ? "This is a mock quote for the word: $word" : 'This is a mock quote for testing purposes.'
        ];
    }
}
