<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'image', 'alt', 'meta_title', 'meta_keyword', 'meta_description', 'og_title', 'og_description', 'og_image', 'status', 'flag', 'hsn', 'sku', 'discount_type', 'discount', 'regular_price', 'sale_price', 'tag', 'short_desc', 'tax_status', 'tax_class', 'in_stock', 'stock', 'low_stock', 'sold_individually', 'weight', 'length', 'width', 'height', 'shipping_class', 'insurance', 'hazardous', 'head_schema', 'body_schema', 'minimum_qty', 'variant'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\ProductReview', 'product_id', 'id');
    }
}
