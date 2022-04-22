<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'title', 'description', 'image', 'meta_title', 'meta_description', 'keywords', 'tags', 'publish_date', 'alt', 'status', 'flag', 'category', 'og_title', 'og_description', 'og_image', 'og_alt', 'head_schema', 'body_schema', 'short_desc'
    ];
}
