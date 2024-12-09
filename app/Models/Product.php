<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','price','categorie_id','image_path'];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Categorie::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(\App\Models\ProductImage::class);
    }


}
