<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use App\Services\WeatherService;
use App\Services\QuoteService;
use App\Services\SearchService;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    protected $newsService;
    protected $weatherService;
    protected $quoteService;
    protected $searchService;

    public function __construct(NewsService $newsService, WeatherService $weatherService, QuoteService $quoteService, SearchService $searchService)
    {
        $this->newsService = $newsService;
        $this->weatherService = $weatherService;
        $this->quoteService = $quoteService;
        $this->searchService = $searchService;
    }

    public function getAllData(Request $request)
    {
        $city = $request->input('city', 'new york');
        $newsCategory = $request->input('news_category', 'general');
        $quoteTopic = $request->input('quote_topic', 'inspiration');

        $news = $this->newsService->getNews($newsCategory);
        $weather = $this->weatherService->getWeather($city);
        $quote = $this->quoteService->getRandomQuote($quoteTopic);

        return response()->json([
            'news' => $news,
            'weather' => $weather,
            'quote' => $quote,
        ]);
    }
}
