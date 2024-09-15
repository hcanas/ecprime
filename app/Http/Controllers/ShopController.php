<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = Product::search($request->get('query'), function ($driver, $query, $options) use ($request) {
            $filters = [
                'status = available',
            ];

            if ($request->get('main_category')) {
                $filters[] = 'main_category = "'.$request->get('main_category').'"';
            }

            if ($request->get('sub_category')) {
                $filters[] = 'sub_category = "'.$request->get('sub_category').'"';
            }

            $options['filter'] = implode(' AND ', $filters);

            return $driver->search($query, $options);
        });

        return Inertia::render('Shop', [
            'products' => $data->paginate($request->get('per_page', 20))
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'image' => Storage::exists('public/images/'.$product->image) ? $product->image : null,
                    'name' => $product->name,
                ]),
        ]);
    }
}
