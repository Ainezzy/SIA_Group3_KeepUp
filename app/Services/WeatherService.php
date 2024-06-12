<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;

class WeatherService
{
    protected $client;
    protected $apiKey;
    protected $apiHost;
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->client = new Client();
        $this->apiKey = '5164abd0bbmshc95f63dc5d1553fp12000cjsne250ceceb573';
        $this->apiHost = 'weather-api138.p.rapidapi.com';
        $this->logger = $logger;
    }

    public function getWeather($city)
    {
        $useMockData = env('USE_MOCK_DATA', false);
        $this->logger->info('USE_MOCK_DATA value:', ['value' => $useMockData]);

        if ($useMockData) {
            $this->logger->info('Using mock data for weather');
            return $this->getMockWeather($city);
        }

        try {
            $response = $this->client->request('GET', "https://{$this->apiHost}/weather", [
                'headers' => [
                    'x-rapidapi-host' => $this->apiHost,
                    'x-rapidapi-key' => $this->apiKey,
                ],
                'query' => [
                    'city_name' => $city
                ],
                'verify' => false
            ]);

            $data = json_decode($response->getBody(), true);

            $temperatureFahrenheit = $data['main']['temp'];
            $temperatureCelsius = round((5/9) * ($temperatureFahrenheit - 32), 2);

            // Parse the response to extract the necessary information
            if (isset($data['main']) && isset($data['wind']) && isset($data['sys']) && isset($data['name'])) {
                return [
                    'temperature' => $temperatureCelsius,
                    'humidity' => $data['main']['humidity'],
                    'windSpeed' => $data['wind']['speed'],
                    'country' => $data['sys']['country'],
                    'city' => $data['name']
                ];
            } else {
                throw new \Exception('Required weather data not found');
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $message = json_decode($response->getBody()->getContents(), true)['message'] ?? 'Unknown error';

            throw new \Exception("Error fetching weather data: $message", $statusCode);
        } catch (\Exception $e) {
            throw new \Exception('Error fetching weather data: ' . $e->getMessage(), 500);
        }
    }

    private function getMockWeather($city)
    {
        $this->logger->info('Returning mock weather data', ['city' => $city]);

        return [
            'temperature' => 22.5,
            'humidity' => 60,
            'windSpeed' => 5.5,
            'country' => 'Mock Country',
            'city' => $city
        ];
    }
}
