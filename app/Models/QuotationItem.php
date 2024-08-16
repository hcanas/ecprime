<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'name',
        'description',
        'price',
        'quantity',
        'measurement_unit',
        'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $product) {
            $product->setAttribute('last_modified_by_id', Auth::user()->id);
        });
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(QuotationItem::class);
    }
}
