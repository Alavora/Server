<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'shop_id'];

    public function items()
    {
        return $this->hasMany(Items::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }


    public function units()
    {
        return $this->belongsToMany(Unit::class)->withPivot('price');
    }
}
