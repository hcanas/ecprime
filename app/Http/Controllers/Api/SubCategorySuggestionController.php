<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubCategorySuggestionController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $sub_categories = Category::whereNotNull('parent_id')
            ->where('name', 'LIKE', '%'.$request->get('query').'%');

        if ($request->get('main_category')) {
            $sub_categories->whereHas('parent', function ($query) use ($request) {
                $query->where('name', 'LIKE', '%'.$request->get('main_category'));
            });
        }

        return response()->json($sub_categories->orderBy('name')
            ->limit(5)
            ->get()
            ->pluck('name')
        );
    }
}
