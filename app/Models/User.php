<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUlids, Notifiable;

    public const ROLE_PLAYER = 'player';

    public const ROLE_SCOUT = 'scout';

    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isPlayer(): bool
    {
        return $this->role === self::ROLE_PLAYER;
    }

    public function isScout(): bool
    {
        return $this->role === self::ROLE_SCOUT;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Get the player profile associated with the user.
     */
    public function playerProfile(): HasOne
    {
        return $this->hasOne(PlayerProfile::class);
    }

    /**
     * Get the scout profile associated with the user.
     */
    public function scoutProfile(): HasOne
    {
        return $this->hasOne(ScoutProfile::class);
    }

    /**
     * Get the scouting interests sent by the user (as a scout).
     */
    public function sentScoutingInterests(): HasMany
    {
        return $this->hasMany(ScoutingInterest::class, 'scout_id');
    }

    /**
     * Get the favorites saved by the user (as a scout).
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'scout_id');
    }

    /**
     * Get the player profiles favorited by the user (as a scout).
     */
    public function favoritePlayers(): BelongsToMany
    {
        return $this->belongsToMany(PlayerProfile::class, 'favorites', 'scout_id', 'player_profile_id')
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
