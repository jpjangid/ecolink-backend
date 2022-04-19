<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinksOnPage extends Model
{
    use HasFactory;

    protected $fillable = ['page_id', 'link_id'];
    
    public function relatedPage()
    {
        return $this->belongsTo('App\Models\Page', 'link_id');
    }
}
