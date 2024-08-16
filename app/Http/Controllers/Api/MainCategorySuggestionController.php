<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainCategorySuggestionController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(Category::whereNull('parent_id')
            ->where('name', 'LIKE', '%'.$request->get('query').'%')
            ->orderBy('name')
            ->limit(5)
            ->get()
            ->pluck('name')
        );
    }
}
