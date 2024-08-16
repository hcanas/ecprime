<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
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
            'category' => $this->category->name,
            'status' => $this->status,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $product) {
            $product->setAttribute('last_modified_by_id', Auth::user()->id);
        });
    }

    public function measurementUnit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class);
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
