<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['price', 'msrp'];


    public function product()
    {
        $this->belongsTo(Product::class);
    }
}
