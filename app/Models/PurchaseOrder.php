<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class PurchaseOrder extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'reference_number',
        'quotation_id',
        'payment_details',
        'delivery_date',
        'status',
        'last_modified_by_id',
    ];

    protected static function booted(): void
    {
        static::updating(function (self $purchase_order) {
            $purchase_order->setAttribute('last_modified_by_id', Auth::user()->id);
            $purchase_order->logActivity();
        });

        static::creating(function (self $purchase_order) {
            $purchase_order->setAttribute('last_modified_by_id', Auth::user()->id);
            UserActivity::create([
                'user_id' => Auth::user()->id,
                'details' => 'Created purchase order with reference number ' . $purchase_order->reference_number . '.',
            ]);
        });
    }

    public function logActivity(): void
    {
        if ($this->status === 'delivered') {
            $details = 'Delivered purchase order with reference number ' . $this->reference_number . '.';
        } elseif ($this->status === 'cancelled') {
            $details = 'Cancelled purchase order with reference number ' . $this->reference_number . '.';
        } else {
            $details = 'Updated purchase order with reference number ' . $this->reference_number . '.';
        }

        UserActivity::create([
            'user_id' => Auth::user()->id,
            'details' => $details,
        ]);
    }

    public function toSearchableArray()
    {
        return [
            'reference_number' => $this->reference_number,
            'customer' => $this->quotation->customer,
            'quotation_reference_number' => $this->quotation->reference_number,
            'payment_details' => $this->payment_details,
            'delivery_date' => $this->delivery_date,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
        ];
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    function lastModifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_modified_by_id');
    }
}
