<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Product::search($request->get('query'), function ($driver, $query, $options) use ($request) {
            $filters = [];

            if (Auth::user()->role === 'affiliate') {
                $filters[] = 'status = unavailable';
            } elseif ($request->get('status')) {
                $filters[] = 'status = '.$request->get('status');
            }

            if ($request->get('main_category')) {
                $filters[] = 'main_category = "'.$request->get('main_category').'"';
            }

            if ($request->get('sub_category')) {
                $filters[] = 'sub_category = "'.$request->get('sub_category').'"';
            }

            $options['filter'] = implode(' AND ', $filters);
            $options['sort'] = ['updated_at:desc'];

            return $driver->search($query, $options);
        });

        return Inertia::render('Products/Index', [
            'can' => [
                'create_product' => Auth::user()->can('create', Product::class),
                'edit_product' => Auth::user()->can('update', new Product()),
            ],
            'products' => $data->paginate($request->get('per_page', 10))
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'image' => Storage::exists('public/images/'.$product->image) ? $product->image : null,
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
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Product::class);

        return Inertia::render('Products/NewProduct');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        try {
            DB::beginTransaction();

            $measurement_unit_id = $this->getMeasurementUnitId($request->validated('measurement_unit'));
            $main_category_id = $this->getCategoryId($request->validated('main_category'));

            if ($request->validated('sub_category')) {
                $sub_category_id = $this->getCategoryId($request->validated('sub_category'), $main_category_id);
            }

            $filename = bin2hex(random_bytes(16)).'.webp';

            if ($request->validated('image')) {
                $file = $request->file('image');

                $this->uploadImage($file, $filename);
            }

            Product::create([
                'image' => $filename,
                'name' => $request->validated('name'),
                'description' => $request->validated('description'),
                'price' => $request->validated('price'),
                'measurement_unit_id' => $measurement_unit_id,
                'category_id' => $sub_category_id ?? $main_category_id,
                'status' => $request->validated('status'),
            ]);

            DB::commit();

            return redirect()->route('products.index')->with('message', [
                'content' => $request->validated('name') . ' has been created.',
                'type' => 'success',
            ]);
        } catch (\Throwable $e) {
            logger($e);
            return back()->with('message', [
                'content' => 'Failed to create product.',
                'type' => 'error',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);

        $product->load('measurementUnit', 'category.parent');

        return Inertia::render('Products/EditProduct', [
            'product' => [
                'id' => $product->id,
                'image' => Storage::exists('public/images/'.$product->image) ? $product->image : null,
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
                'last_modified_by' => $product->lastModifiedBy,
                'updated_at' => $product->updated_at,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);

        try {
            DB::beginTransaction();

            $measurement_unit_id = $this->getMeasurementUnitId($request->validated('measurement_unit'));
            $main_category_id = $this->getCategoryId($request->validated('main_category'));

            if ($request->validated('sub_category')) {
                $sub_category_id = $this->getCategoryId($request->validated('sub_category'), $main_category_id);
            }

            if ($request->validated('image')) {
                $file = $request->file('image');
                $this->uploadImage($file, $product->image);
            }

            $product->fill([
                'name' => $request->validated('name'),
                'description' => $request->validated('description'),
                'price' => $request->validated('price'),
                'measurement_unit_id' => $measurement_unit_id,
                'category_id' => $sub_category_id ?? $main_category_id,
                'status' => $request->validated('status'),
            ])->save();

            DB::commit();

            return back()->with('message', [
                'content' => 'Product details have been updated.',
                'type' => 'success',
            ]);
        } catch (\Throwable $e) {
            logger($e);
            return back()->with('message', [
                'content' => 'Failed to update product.',
                'type' => 'error',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index')
            ->with('message', [
                'content' => $product->name.' has been deleted.',
                'type' => 'error',
            ]);
    }

    private function uploadImage(UploadedFile $file, string $filename): void
    {
        $image = Image::read($file);
        $image->resize(640, 640);
        Storage::put('public/images/'.$filename, (string) $image->encode());
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
