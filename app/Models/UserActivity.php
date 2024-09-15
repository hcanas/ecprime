<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class UserActivity extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'details',
    ];

    public function toSearchableArray()
    {
        return [
            'user_id' => $this->user_id,
            'details' => $this->details,
            'created_at' => $this->created_at,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
