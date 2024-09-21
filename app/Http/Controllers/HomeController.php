<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Home', [
            'trending_products' => (new Product())->popular(8, true)->map(fn ($product) => [
                'id' => $product->product_id,
                'image' => Storage::exists('public/images/'.$product->image) ? $product->image : null,
                'name' => $product->name,
            ]),
        ]);
    }
}
