<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'user_id', 'status'];

    public const STATUS_UNCONFIRMED = 0;
    public const STATUS_CONFIRMED = 1;
    public const STATUS_PREPARING = 2;
    public const STATUS_READY = 3;

    /**
     * Links the basket to it's items
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Links the basket to it's shop.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Links the basket to it's user (buyer)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
