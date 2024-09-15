<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\NewUserNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        if (empty($this->password)) {
            $url = route('password.reset', [ 'token' => $token ]);
            $this->notify(new NewUserNotification($url));
        } else {
            parent::sendPasswordResetNotification($token);
        }
    }

    protected static function booted(): void
    {
        static::creating(function (self $user) {
            $user->setAttribute('last_modified_by_id', Auth::user()?->id);
            UserActivity::create([
                'user_id' => Auth::user()->id,
                'details' => 'Registered new user with email address ' . $user->email . '.',
            ]);
        });

        static::updating(function (self $user) {
            $user->setAttribute('last_modified_by_id', Auth::user()?->id);
            if ($user->isDirty('role')) {
                UserActivity::create([
                    'user_id' => Auth::user()->id,
                    'details' => 'Updated role of user with email address ' . $user->email . '.',
                ]);
            } elseif ($user->isDirty('status')) {
                if ($user->status === 'active') {
                    UserActivity::create([
                        'user_id' => Auth::user()->id,
                        'details' => 'Restored access of user with email address ' . $user->email . '.',
                    ]);
                } elseif ($user->status === 'restricted') {
                    UserActivity::create([
                        'user_id' => Auth::user()->id,
                        'details' => 'Restricted access of user with email address ' . $user->email . '.',
                    ]);
                }
            }
        });

        static::deleting(function (self $user) {
            UserActivity::create([
                'user_id' => Auth::user()->id,
                'details' => 'Deleted user with email address ' . $user->email . '.',
            ]);
        });
    }

    public function lastModifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_modified_by_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'last_modified_by_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(UserActivity::class);
    }
}
