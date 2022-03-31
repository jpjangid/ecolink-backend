<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug'];

    public function sublink()
    {
        return $this->hasMany('App\Models\Page', 'category', 'id');
    }
}
