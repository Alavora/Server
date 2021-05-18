<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $fillable = ["name", "description", "cif", "market_image"];

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }
}
