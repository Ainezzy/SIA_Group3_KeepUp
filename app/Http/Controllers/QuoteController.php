<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuoteService;
use Illuminate\Support\Facades\Log;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function getRandomQuote()
    {
        try {
            $quote = $this->quoteService->getRandomQuote();
            return response()->json($quote);
        } catch (\Exception $e) {
            Log::error('Error fetching random quote: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching random quote: ' . $e->getMessage()], 500);
        }
    }

    public function getRandomQuoteByWord($word)
    {
        try {
            $quote = $this->quoteService->getRandomQuote($word);
            return response()->json($quote);
        } catch (\Exception $e) {
            Log::error('Error fetching quote for word: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching quote for word: ' . $e->getMessage()], 500);
        }
    }
}
