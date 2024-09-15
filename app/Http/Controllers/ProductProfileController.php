<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductProfileController extends Controller
{
    public function __invoke(Product $product)
    {
        return Inertia::render('ProductProfile', [
            'product' => [
                'id' => $product->id,
                'image' => Storage::exists('public/images/'.$product->image) ? $product->image : null,
                'name' => $product->name,
                'description' => $product->description,
                'measurement_unit' => $product->measurementUnit->name,
            ],
        ]);
    }
}
