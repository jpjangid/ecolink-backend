<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'email', 'mobile', 'address', 'landmark', 'country', 'state', 'city', 'zip', 'address_type'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
