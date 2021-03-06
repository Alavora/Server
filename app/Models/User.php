<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SanctumHasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Links the user to its baskets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }

    /**
     * Links the seller to it's owned shops
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedShops()
    {
        return $this->hasMany(Shop::class, 'shop_owner', 'owner_id', 'shop_id');
    }

    /**
     * Links the distributor to it's distributed baskets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function distributedBaskets()
    {
        return $this->hasMany(Basket::class);
    }
}
