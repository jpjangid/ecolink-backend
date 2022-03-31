<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'parent_id', 'description', 'image', 'meta_title', 'meta_description', 'keywords', 'alt', 'status', 'flag', 'og_title', 'og_description', 'og_image', 'og_alt', 'type', 'short_desc'
    ];

    public function subcategory()
    {
        return $this->hasMany('App\Models\Category', 'parent_id')->orderBy('name', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product','parent_id', 'id');
    }
}
