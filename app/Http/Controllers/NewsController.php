<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NewsService;

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
            $category = $request->query('category', 'sports');
            $result = $this->newsService->getNews($category);
            return response()->json($result);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ? $e->getCode() : 500;
            return response()->json(['error' => $e->getMessage()], $statusCode);
        }
    }
}
