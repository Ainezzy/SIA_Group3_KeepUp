<?php

namespace App\Http\Controllers;

use App\Services\NewsService; // Custom service for fetching news
use App\Services\WeatherService; // Custom service for fetching weather data
use App\Services\QuoteService; // Custom service for fetching quotes
use Illuminate\Http\Request; // For handling HTTP requests

// Define the GatewayController class, which extends the base Controller class
class GatewayController extends Controller
{
    // Properties to hold instances of the various services
    protected $newsService;
    protected $weatherService;
    protected $quoteService;
    protected $searchService;

    // Constructor method to initialize the service instances
    public function __construct(NewsService $newsService, WeatherService $weatherService, QuoteService $quoteService, SearchService $searchService)
    {
        // Assign the passed service instances to the controller's properties
        $this->newsService = $newsService;
        $this->weatherService = $weatherService;
        $this->quoteService = $quoteService;
        $this->searchService = $searchService;
    }

    // Method to handle GET requests for fetching all data (news, weather, quote)
    public function getAllData(Request $request)
    {
        // Retrieve the 'city', 'news_category', and 'quote_topic' input parameters from the request
        $city = $request->input('city');
        $newsCategory = $request->input('news_category');
        $quoteTopic = $request->input('quote_topic');

        // Use the NewsService to fetch news based on the category
        $news = $this->newsService->getNews($newsCategory);

        // Use the WeatherService to fetch weather data for the specified city
        $weather = $this->weatherService->getWeather($city);

        // Use the QuoteService to fetch a random quote based on the topic
        $quote = $this->quoteService->getRandomQuote($quoteTopic);

        // Return the aggregated result as a JSON response
        return response()->json([
            'news' => $news,
            'weather' => $weather,
            'quote' => $quote,
        ]);
    }
}
