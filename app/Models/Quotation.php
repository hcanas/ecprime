<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class Quotation extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'reference_number',
        'customer_id',
        'status',
        'last_modified_by_id',
    ];

    public function toSearchableArray()
    {
        return [
            'reference_number' => $this->reference_number,
            'customer' => $this->customer()->first(),
            'status' => $this->status,
            'updated_at' => $this->updated_at,
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (self $quotation) {
            $quotation->setAttribute('last_modified_by_id', Auth::user()->id);
        });

        static::updated(function (self $quotation) {
            $quotation->logActivity();
        });
    }

    public function logActivity(): void
    {
        if ($this->status === 'pending') {
            $details = 'Requested approval of quotation with reference number ' . $this->reference_number . '.';
        } elseif ($this->status === 'sent') {
            $details = 'Emailed the quotation with reference number '
                .$this->reference_number
                .' to the customer.';
        } elseif ($this->status === 'confirmed') {
            $details = 'Confirmed quotation with reference number ' . $this->reference_number . '.';
        } elseif ($this->status === 'cancelled') {
            $details = 'Cancelled quotation with reference number ' . $this->reference_number . '.';
        } else {
            $details = 'Updated quotation with reference number ' . $this->reference_number . '.';
        }

        UserActivity::create([
            'user_id' => Auth::user()->id,
            'details' => $details,
        ]);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    function lastModifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_modified_by_id');
    }
}
