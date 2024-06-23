<?php

namespace App\Http\Controllers;

use App\Services\WeatherService; // Custom service for fetching weather data
use Illuminate\Http\Request; // For handling HTTP requests
use Illuminate\Support\Facades\Log; // For logging errors

// Define the WeatherController class, which extends the base Controller class
class WeatherController extends Controller
{
    // Property to hold an instance of WeatherService
    protected $weatherService;

    // Constructor method to initialize the WeatherService instance
    public function __construct(WeatherService $weatherService)
    {
        // Assign the passed WeatherService instance to the controller's property
        $this->weatherService = $weatherService;
    }

    // Method to handle GET requests for fetching weather data
    public function getWeather(Request $request)
    {
        // Retrieve the 'city' input parameter from the request
        $city = $request->input('city');
        try {
            // Check if the 'city' parameter is provided
            if ($city) {
                // Use the WeatherService to fetch weather data for the specified city
                $weatherData = $this->weatherService->getWeather($city);

                // Return the result as a JSON response
                return response()->json($weatherData);
            } else {
                // Return an error response as JSON with a 400 status code if 'city' is not provided
                return response()->json(['error' => 'City parameter is required.'], 400);
            }
        } catch (\Exception $e) {
            // Log any errors that occur while fetching the weather data
            Log::error('Error fetching weather: ' . $e->getMessage());

            // Return an error response as JSON with a 500 status code
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Method to handle GET requests for fetching weather data by city
    public function getWeatherByCity($city)
    {
        try {
            // Use the WeatherService to fetch weather data for the specified city
            $weatherData = $this->weatherService->getWeather($city);

            // Return the result as a JSON response
            return response()->json($weatherData);
        } catch (\Exception $e) {
            // Log any errors that occur while fetching the weather data for the specified city
            Log::error('Error fetching weather for city: ' . $e->getMessage());

            // Return an error response as JSON with a 500 status code
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
