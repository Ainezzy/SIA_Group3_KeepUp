<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function getNews(Request $request)
    {
        try {
            $category = $request->query('category');
            $result = $this->newsService->getNews($category);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error fetching news: ' . $e->getMessage());
            $statusCode = $e->getCode() ? $e->getCode() : 500;
            return response()->json(['error' => $e->getMessage()], $statusCode);
        }
    }

    public function getNewsByCategory($category)
    {
        try {
            $result = $this->newsService->getNews($category);
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error fetching news for category: ' . $e->getMessage());
            $statusCode = $e->getCode() ? $e->getCode() : 500;
            return response()->json(['error' => $e->getMessage()], $statusCode);
        }
    }
}
