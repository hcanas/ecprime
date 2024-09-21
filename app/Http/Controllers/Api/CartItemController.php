<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MeasurementUnit;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CartItemController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if ($request->get('ids')) {
            $ids = explode(',', $request->get('ids'));
            $items = Product::select('id', 'image', 'name', 'description')
                ->addSelect(['measurement_unit' => MeasurementUnit::select('name')
                    ->whereColumn('id', 'products.measurement_unit_id')
                    ->limit(1)
                ])
                ->whereIn('id', $ids)
                ->get()
                ->map(fn ($product) => [
                    ...$product->toArray(),
                    'image' => Storage::exists('public/images/'.$product->image) ? $product->image : null,
                ]);
        }

        return response()->json($items ?? []);
    }
}
