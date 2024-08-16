<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json(
            Category::whereNull('parent_id')
                ->with(['subCategories' => function ($query) {
                    return $query->orderBy('name');
                }])
                ->orderBy('name')
                ->get()
        );
    }
}
