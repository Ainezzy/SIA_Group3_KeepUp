<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // For handling HTTP requests
use App\Services\NewsService; // Custom service for fetching news
use Illuminate\Support\Facades\Log; // For logging errors

class NewsController extends Controller
{
    // Property to hold an instance of NewsService
    protected $newsService;

    // Constructor method to initialize the NewsService instance
    public function __construct(NewsService $newsService)
    {
        // Assign the passed NewsService instance to the controller's property
        $this->newsService = $newsService;
    }

    // Method to handle GET requests for fetching news
    public function getNews(Request $request)
    {
        try {
            // Retrieve the 'category' query parameter from the request
            $category = $request->query('category');

             // Use the NewsService to fetch news based on the category
            $result = $this->newsService->getNews($category);

            // Return the result as a JSON response
            return response()->json($result);
        } catch (\Exception $e) {
            // Log any errors that occur while fetching the news
            Log::error('Error fetching news: ' . $e->getMessage());

            // Determine the status code to return, defaulting to 500 if none is provided
            $statusCode = $e->getCode() ? $e->getCode() : 500;

             // Return an error response as JSON
            return response()->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    // Method to handle GET requests for fetching news by a specific category
    public function getNewsByCategory($category)
    {
        try {
            // Use the NewsService to fetch news based on the provided category
            $result = $this->newsService->getNews($category);

            // Return the result as a JSON response
            return response()->json($result);
        } catch (\Exception $e) {
            // Log any errors that occur while fetching the news for the specific category
            Log::error('Error fetching news for category: ' . $e->getMessage());

            // Determine the status code to return, defaulting to 500 if none is provided
            $statusCode = $e->getCode() ? $e->getCode() : 500;

             // Return an error response as JSON
            return response()->json(['error' => $e->getMessage()], $statusCode);
        }
    }
}
