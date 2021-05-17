<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }
}
