<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['path','product_id'];

    public function product()
    {
        $this->belongsTo(\App\Models\Product::class);
    }
}
