<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuoteService;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function getRandomQuote(Request $request)
    {
        $topic = $request->input('topic', 'life');

        try {
            $quote = $this->quoteService->getRandomQuote($topic);
            return response()->json($quote);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching quote: ' . $e->getMessage()], 500);
        }
    }

    public function getQuotesByCategory(Request $request, $categoryName)
    {
        try {
            $quotes = $this->quoteService->getQuotesByCategory($categoryName);
            return response()->json($quotes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching quotes: ' . $e->getMessage()], 500);
        }
    }
}
