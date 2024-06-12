<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getWeather(Request $request)
    {
        $city = $request->input('city');
        try {
            if ($city) {
                $weatherData = $this->weatherService->getWeather($city);
                return response()->json($weatherData);
            } else {
                return response()->json(['error' => 'City parameter is required.'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching weather: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWeatherByCity($city)
    {
        try {
            $weatherData = $this->weatherService->getWeather($city);
            return response()->json($weatherData);
        } catch (\Exception $e) {
            Log::error('Error fetching weather for city: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
