<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'price', 'image', 'shop_id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
