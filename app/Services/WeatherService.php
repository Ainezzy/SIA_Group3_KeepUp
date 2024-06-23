<?php

namespace App\Services;

use GuzzleHttp\Client; // For making HTTP response
use GuzzleHttp\Exception\ClientException; // Handling HTTP client exceptions
use Psr\Log\LoggerInterface; // For logging messages

class WeatherService
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
        $this->apiHost = 'weather-api138.p.rapidapi.com'; // Sets the API host URL
        $this->logger = $logger; // Sets the logger instance
    }

    // Method to fetch weather data for a given city
    public function getWeather($city)
    {
        $useMockData = env('USE_MOCK_DATA', false); // Retrieves the USE_MOCK_DATA, defaulting to false if not set
        $this->logger->info('USE_MOCK_DATA value:', ['value' => $useMockData]); // Logs the value of USE_MOCK_DATA

        // Checks if mock data should be used
        if ($useMockData) {
            $this->logger->info('Using mock data for weather'); // Logs that mock data is being used
            return $this->getMockWeather($city); // Returns mock news data if USE_MOCK_DATA is true
        }

        // Starts a try block to attempt the API request
        try {
            $response = $this->client->request('GET', "https://{$this->apiHost}/weather", [ // Makes a GET request to the news API endpoint 
                // Sets the headers for the request
                headers' => [
                    'x-rapidapi-host' => $this->apiHost,
                    'x-rapidapi-key' => $this->apiKey,
                ],
                // Sets the query parameters for the request
                'query' => [
                    'city_name' => $city
                ],
                'verify' => false // Disables SSL certificate verification 
            ]);

            $data = json_decode($response->getBody(), true); // Returns the decoded JSON response body

            $temperatureFahrenheit = $data['main']['temp']; Extracts the temperature in Fahrenheit
            $temperatureCelsius = round((5/9) * ($temperatureFahrenheit - 32), 2); Converts the temperature to Celsius

            // Parse the response to extract the necessary information
            if (isset($data['main']) && isset($data['wind']) && isset($data['sys']) && isset($data['name'])) {
                // Returns an array with the weather data 
                return [
                    'temperature' => $temperatureCelsius,
                    'humidity' => $data['main']['humidity'],
                    'windSpeed' => $data['wind']['speed'],
                    'country' => $data['sys']['country'],
                    'city' => $data['name']
                ];
            } else {
                throw new \Exception('Required weather data not found'); // Throws an exception if the necessary fields are not present
            }
        } catch (ClientException $e) { // Catches Guzzle client exceptions
            $response = $e->getResponse(); // Gets the response object from the exception
            $statusCode = $response->getStatusCode(); //  Gets the status code from the response
            $message = json_decode($response->getBody()->getContents(), true)['message'] ?? 'Unknown error'; // Extracts the error message from the response

            throw new \Exception("Error fetching weather data: $message", $statusCode); // Throws a new exception with the error message and status code
        } catch (\Exception $e) { // // Catches any other exceptions
            throw new \Exception('Error fetching weather data: ' . $e->getMessage(), 500); // // Throws a new exception with a generic error message and a status code of 500 
        }
    }

    // Private method to return mock weather data
    private function getMockWeather($city)
    {
        $this->logger->info('Returning mock weather data', ['city' => $city]); // Logs that mock weather data is being returned

        // Returns an array with mock weather data
        return [
            'temperature' => 22.5,
            'humidity' => 60,
            'windSpeed' => 5.5,
            'country' => 'Mock Country',
            'city' => $city
        ];
    }
}
