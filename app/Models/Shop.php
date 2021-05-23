<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ["name", "phone", "cif", "shop_image", "address"];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function sellers()
    {
        return $this->belongsToMany(User::class, 'shop_owner', 'shop_id', 'owner_id');
    }
}
