<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::search($request->get('query'), function ($meilisearch, $query, $options) use ($request) {
            $filters = [];

            if ($request->get('status')) {
                $filters[] = 'status = '.$request->get('status');
            }

            if ($request->get('category')) {
                $filters[] = 'category = "'.$request->get('category').'"';
            }

            $options['filter'] = implode(' AND ', $filters);

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('Products/Index', [
            'can' => [
                'create_product' => Auth::user()->can('create', Product::class),
            ],
            'products' => $products->paginate($request->get('per_page', 10))
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'measurement_unit' => $product->measurementUnit->name,
                    'category' => $product->category->name,
                    'status' => $product->status,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Product::class);

        return Inertia::render('Products/NewProductForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        $measurement_unit_id = $this->getMeasurementUnitId($request->validated('measurement_unit'));
        $main_category_id = $this->getCategoryId($request->validated('main_category'));

        if ($request->validated('sub_category')) {
            $sub_category_id = $this->getCategoryId($request->validated('sub_category'), $main_category_id);
        }

        Product::create([
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'price' => $request->validated('price'),
            'measurement_unit_id' => $measurement_unit_id,
            'category_id' => $sub_category_id ?? $main_category_id,
            'status' => $request->validated('status'),
        ]);

        return back()->with([
            'message' => $request->validated('name').' has been created.',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);

        $product->load('measurementUnit', 'category.parent');

        return Inertia::render('Products/EditProductForm', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'measurement_unit' => $product->measurementUnit->name,
                'main_category' => $product->category->parent
                    ? $product->category->parent->name
                    : $product->category->name,
                'sub_category' => $product->category->parent
                    ? $product->category->name
                    : null,
                'status' => $product->status,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);

        $measurement_unit_id = $this->getMeasurementUnitId($request->validated('measurement_unit'));
        $main_category_id = $this->getCategoryId($request->validated('main_category'));

        if ($request->validated('sub_category')) {
            $sub_category_id = $this->getCategoryId($request->validated('sub_category'), $main_category_id);
        }

        $product->fill([
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'price' => $request->validated('price'),
            'measurement_unit_id' => $measurement_unit_id,
            'category_id' => $sub_category_id ?? $main_category_id,
            'status' => $request->validated('status'),
        ])->save();

        return back()->with([
            'message' => 'Product details have been updated.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index')
            ->with('message', $product->name.' has been deleted.');
    }

    private function getMeasurementUnitId(string $name): int
    {
        return MeasurementUnit::firstOrCreate([
            'name' => $name,
        ])->id;
    }

    private function getCategoryId(string $name, ?int $parent_id = null): int
    {
        return Category::firstOrCreate([
            'name' => $name,
            'parent_id' => $parent_id,
        ])->id;
    }
}
