<?php

namespace App\Models;

use App\Models\ProductAdditional;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'price',
        'description',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function addition()
    {
        return $this->hasOne(ProductAdditional::class);
    }

    public function like()
    {
        return $this->belongsTo(Like::class);
    }
}
