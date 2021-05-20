<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    public const STATUS_UNCONFIRMED = 0;
    public const STATUS_CONFIRMED = 1;
    public const STATUS_PREPARING = 2;
    public const STATUS_READY = 3;

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
