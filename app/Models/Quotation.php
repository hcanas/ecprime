<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $product) {
            $product->setAttribute('last_modified_by_id', Auth::user()->id);
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }
}
