<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'description',
        'brand',
        'price',
        'quantity',
        'measurement_unit',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $product) {
            $product->setAttribute('last_modified_by_id', Auth::user()->id);
        });
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
