<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'description', 'image', 'alt', 'meta_title', 'meta_description', 'keywords', 'tags', 'og_title', 'og_description', 'og_image', 'og_alt', 'status', 'flag', 'category'
    ];

    public function pagecategory()
    {
        return $this->belongsTo('App\Models\LinkCategory', 'category');
    }
}
