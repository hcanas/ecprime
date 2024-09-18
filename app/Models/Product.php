<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'image',
        'name',
        'description',
        'price',
        'measurement_unit_id',
        'category_id',
        'status',
    ];

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'measurement_unit' => $this->measurementUnit->name,
            'main_category' => $this->category->parent
                ? $this->category->parent->name
                : $this->category->name,
            'sub_category' => $this->category->parent
                ? $this->category->name
                : null,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
        ];
    }

    public function popular(int $count): Collection
    {
        return DB::table('quotation_items')
            ->selectRaw('max(quotation_items.image) AS image, count(quotation_items.id) AS count, quotation_items.name, products.id AS product_id')
            ->leftJoin('products', 'products.name', 'quotation_items.name')
            ->whereRaw('quotation_items.created_at > DATE_SUB(CURDATE(), INTERVAL 30 DAY)')
            ->whereRaw('products.id IS NOT NULL')
            ->whereRaw('products.status = "available"')
            ->groupBy('quotation_items.name')
            ->orderBy('count', 'desc')
            ->limit($count ?: 10)
            ->get();
    }

    protected static function booted(): void
    {
        static::creating(function (self $product) {
            $product->setAttribute('last_modified_by_id', Auth::user()->id);
            UserActivity::create([
                'user_id' => Auth::user()->id,
                'details' => 'Added the product ' . $product->name . '.',
            ]);
        });

        static::updating(function (self $product) {
            $product->setAttribute('last_modified_by_id', Auth::user()->id);
            UserActivity::create([
                'user_id' => Auth::user()->id,
                'details' => 'Updated the product ' . $product->name . '.',
            ]);
        });

        static::deleting(function (self $product) {
            UserActivity::create([
                'user_id' => Auth::user()->id,
                'details' => 'Deleted the product ' . $product->name . '.',
            ]);
        });
    }

    public function measurementUnit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function lastModifiedBy()
    {
        return $this->belongsTo(User::class);
    }
}
