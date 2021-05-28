<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    use HasFactory;

    protected $fillable = ['shop_id',  'status', 'product_id', 'unit_id', 'price'];

    public const STATUS_UNCONFIRMED = 0;
    public const STATUS_CONFIRMED = 1;
    public const STATUS_PREPARING = 2;
    public const STATUS_READY = 3;

    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
