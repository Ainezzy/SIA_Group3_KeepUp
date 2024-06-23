<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // For handling HTTP requests
use App\Services\QuoteService; // Custom service for fetching quotes
use Illuminate\Support\Facades\Log; // For logging errors

// Define the QuoteController class, which extends the base Controller class
class QuoteController extends Controller
{
    // Property to hold an instance of QuoteService
    protected $quoteService;

    // Constructor method to initialize the QuoteService instance
    public function __construct(QuoteService $quoteService)
    {
        // Assign the passed QuoteService instance to the controller's property
        $this->quoteService = $quoteService;
    }

    // Method to handle GET requests for fetching a random quote
    public function getRandomQuote()
    {
        try {
            // Use the QuoteService to fetch a random quote
            $quote = $this->quoteService->getRandomQuote();

            // Return the result as a JSON response
            return response()->json($quote);
        } catch (\Exception $e) {
            // Log any errors that occur while fetching the random quote
            Log::error('Error fetching random quote: ' . $e->getMessage());

            // Return an error response as JSON with a 500 status code
            return response()->json(['error' => 'Error fetching random quote: ' . $e->getMessage()], 500);
        }
    }

    // Method to handle GET requests for fetching a random quote that includes a specific word
    public function getRandomQuoteByWord($word)
    {
        try {
            // Use the QuoteService to fetch a random quote that includes the specified word
            $quote = $this->quoteService->getRandomQuote($word);

            // Return the result as a JSON response
            return response()->json($quote);
        } catch (\Exception $e) {
            // Log any errors that occur while fetching the quote for the specified word
            Log::error('Error fetching quote for word: ' . $e->getMessage());

            // Return an error response as JSON with a 500 status code
            return response()->json(['error' => 'Error fetching quote for word: ' . $e->getMessage()], 500);
        }
    }
}
